# Laravel API — Agent Instructions

## 1. Purpose and Instruction Priority

This repository follows a layered Laravel API architecture:

**Controller → Service → Repository → Eloquent Model**

Additional components have distinct roles: **Form Requests** validate input, **Policies** authorize operations, **API Resources** shape output, and **KnownException** represents anticipated business errors.

These project-specific architectural rules take precedence over generic coding suggestions when they conflict. Follow higher-priority system/developer instructions and explicit user requests. Do not silently replace this architecture with Actions, CQRS, DTO-only services, or another pattern.

Before editing, inspect the existing implementation, dependencies, migrations, routes, tests, and conventions. Extend existing classes instead of generating redundant alternatives. Apply these rules to API functionality; do not force unused classes onto models without corresponding operations.

## 2. Environment and Laravel Boost

- Use the project's installed PHP, Composer, Laravel, and package versions; check `composer.json` and the available runtime first.
- Do **not** install or upgrade PHP, Composer, Laravel, Laravel Boost, or other dependencies just to start a task. Request approval when an installation or version change is actually required.
- If Laravel Boost is installed and its tools/guidelines are available, use them for version-specific framework guidance while preserving the architecture defined here.
- Do **not** automatically run `composer require laravel/boost --dev`, `php artisan boost:install`, or `php artisan boost:update` on ordinary development tasks.
- Treat generated Boost guidance as complementary; never allow regeneration to silently discard these custom project rules.
- Prefer commands supported by the actual environment (for example, Laravel Sail commands when the project runs through Sail).

## 3. Directory and Naming Conventions

For a `User` API module, use these conventional locations when the corresponding class is needed:

```text
app/
├── Models/User.php
├── Http/Controllers/UserController.php
├── Http/Requests/CreateUserRequest.php
├── Http/Requests/UpdateUserRequest.php
├── Http/Resources/UserResource.php
├── Services/UserService.php
├── Repositories/UserRepository.php
├── Policies/UserPolicy.php
└── Exceptions/KnownException.php
```

Use analogous names for other models, such as `Client`, `Doctor`, and `Appointment`. Respect existing application namespaces and directory structure if they differ.

Use standard REST controller methods: `index`, `show`, `store`, `update`, and `destroy`. `create` and `edit` are HTML-form methods and should not be generated for JSON-only API controllers. Services and repositories may use `create`, `update`, and `delete` as operation names.

## 4. Controllers — HTTP Orchestration Only

Controllers must:

- Accept requests, invoke Form Requests, and authorize actions with Laravel Policies (normally via `Gate::authorize()`).
- Inject Services via constructor injection and delegate business operations to them.
- Pass `$request->validated()` to the Service for writes.
- Return API Resources and correct HTTP status codes.
- Handle anticipated and unexpected errors consistently with the project's established controller pattern.

Controllers must **not** contain business rules, direct Eloquent queries, persistence, or duplicated validation. Do not return raw Eloquent models from API endpoints.

Preferred `store` pattern:

```php
public function store(CreateUserRequest $request)
{
    Gate::authorize('create', User::class);

    try {
        $user = $this->userService->create($request->validated());

        return UserResource::make($user)
            ->additional(['message' => 'User created successfully'])
            ->response()
            ->setStatusCode(201);
    } catch (KnownException $e) {
        return response()->json(['message' => $e->getMessage()], 422);
    } catch (\Throwable $e) {
        report($e);

        return response()->json(['message' => 'An unexpected error occurred.'], 500);
    }
}
```

Keep validation and authorization outside the generic unexpected-error catch so Laravel can return its normal 422/401/403 responses. Likewise, allow route-model binding failures to retain their normal 404 behavior. If the project later centralizes exception rendering, keep equivalent semantics without duplicating handlers.

## 5. Form Requests — Input Validation

- Use dedicated Form Requests such as `CreateUserRequest` and `UpdateUserRequest`.
- Validate required fields, formats, types, lengths, uniqueness, and optional fields where appropriate.
- Use `validated()` / `safe()` rather than forwarding unvalidated request input.
- Keep multi-step business decisions in Services, not Form Requests.
- Do not duplicate authorization across the Form Request and Controller without a reason. Follow the module's existing convention.

## 6. Policies — Authorization

- Use model Policies, such as `UserPolicy` and `AppointmentPolicy`, for protected actions.
- Use `Gate::authorize('create', User::class)` for model-level creation and the model instance for record-level actions.
- Policies should decide access; Services should decide business validity.
- Do not turn `AuthorizationException` into `KnownException` or an HTTP 500. Preserve HTTP 403 for forbidden actions and 401 for unauthenticated requests.

## 7. Services — Business Operations

Services coordinate the full business operation without handling HTTP concerns.

- Inject the required Repository or Repositories through the constructor.
- Validate business invariants, coordinate lookups, transform data, and call Repository methods.
- Throw `KnownException` for anticipated business-rule failures where appropriate.
- Return models, collections, scalar values, or domain results — never HTTP responses or API Resources.
- Do not execute direct Eloquent queries or persistence in Services.
- Use `DB::transaction()` at the Service level for atomic multi-write workflows. Transaction coordination is allowed; raw queries and model persistence still belong in Repositories.
- Hash passwords exactly once, following the project's password hashing convention (Service hashing or Eloquent `hashed` cast, not both).
- Services may depend on multiple Repositories; do not artificially restrict a Service to a single Repository.

Example: `UserService::create()` checks relevant business rules through `UserRepository`, prepares the password, calls `UserRepository::create()`, and returns the created `User`.

## 8. Repositories — Queries and Persistence

Repositories own all model database queries and mutations:

- Creation, updates, deletion, fetches, filters, existence checks, pagination queries, and relation-loading queries.
- Use Eloquent unless a query specifically requires another supported database mechanism.
- Explicitly map the attributes accepted for each write; do not blindly call `Model::create($data)` or `$model->update($data)` on arbitrary incoming arrays.
- Centralize persistence field mappings so different workflows do not silently write different attributes.
- Distinguish omitted fields from fields explicitly set to `null` during partial updates; do not overwrite omitted fields.
- Keep required-field rules in Form Requests, business rules, and database constraints, not only in Repository mappings.
- Preserve unique indexes, foreign keys, and other database integrity constraints.
- Do not authorize users, build HTTP responses, return API Resources, or orchestrate business workflows in Repositories.

Example:

```php
public function create(array $data): User
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'],
    ]);
}
```

## 9. API Resources — Response Representation

- Use `UserResource::make($user)` for an individual resource and `UserResource::collection($users)` for collections.
- Explicitly whitelist response attributes. Never expose password hashes, tokens, or protected internal data.
- Use `additional()` to attach response metadata or success messages as appropriate.
- Keep database queries, authorization decisions, and business logic outside Resources.
- Preserve Laravel's pagination metadata when returning paginated collections.

## 10. Expected and Unexpected Errors

Use the shared `app/Exceptions/KnownException.php` for predictable business failures. It should extend an appropriate exception base, such as `\RuntimeException`.

Examples include an unavailable appointment slot, a prohibited state transition, or another failed business invariant.

- Catch `KnownException` in the established HTTP error-handling layer and respond with a safe message. Use 422 when appropriate; use 409 or another status if the semantics clearly require it and the project's error contract supports it.
- Let normal `ValidationException`, `AuthenticationException`, `AuthorizationException`, and `ModelNotFoundException` retain their standard behavior.
- For genuinely unexpected failures, report the exception and return a generic 500 message. Never expose SQL, tracebacks, tokens, or internal exception messages in production API responses.
- Avoid masking a known client error as an unexpected server error.

## 11. HTTP API Conventions

- `200 OK`: successful read or update.
- `201 Created`: newly created resource.
- `204 No Content`: successful deletion without a body.
- `401 Unauthorized`: missing/invalid authentication.
- `403 Forbidden`: action is not permitted.
- `404 Not Found`: requested resource does not exist.
- `409 Conflict`: conflicting resource state, when appropriate.
- `422 Unprocessable Content`: validation or business-rule failure, when appropriate.
- `500 Internal Server Error`: unexpected internal failure.

Keep JSON success and error shapes consistent with the existing API contract.

## 12. Dependency Injection and Boundaries

Dependency direction:

```text
Request → FormRequest → Controller + Policy
                            ↓
                         Service
                            ↓
                      Repository
                            ↓
                      Eloquent Model

Service result → Controller → API Resource → JSON
```

- Use Laravel's container and constructor injection; do not instantiate Services or Repositories manually in Controllers.
- Avoid circular dependencies.
- Do not introduce Repository interfaces unless they are already part of the project or have a concrete reason.
- Prefer clear methods and minimal abstractions over architecture for its own sake.

## 13. Implementation Checklist

When adding or changing a feature:

1. Inspect existing routes, migrations, models, related classes, and tests.
2. Confirm validation, authorization, business rules, and response shape.
3. Create or adjust the Form Request and Policy.
4. Implement or reuse Repository queries and explicit persistence mappings.
5. Implement the Service operation and transaction boundaries if needed.
6. Update the API Resource and Controller; register routes if required.
7. Test successful operations, validation failures, forbidden access, known business failures, and database integrity.
8. Run the relevant project's tests and formatting commands when available; report anything not verified.

Do not generate duplicate controllers, repositories, or services when existing ones can be extended.

## 14. Non-Negotiable Restrictions

- No business workflows or direct database operations in Controllers.
- No direct model queries or persistence in Services.
- No HTTP responses, API Resources, or permission checks inside Repositories.
- No bypassing Form Requests or required Policy checks.
- No raw model exposure in public JSON APIs.
- No mass assignment of unchecked request arrays.
- No leakage of internal unexpected-error details.
- No unrelated dependency changes or infrastructure setup without approval.
- No replacement of the agreed layered architecture without explicit instruction.

## 15. Core Principle

**Controllers orchestrate HTTP. Form Requests validate. Policies authorize. Services enforce business rules. Repositories access and modify data. Eloquent Models represent persisted entities. API Resources define output. KnownException represents anticipated business failures.**
