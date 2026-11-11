// Shared admin UI helpers (no JSX).

export function btn(variant = "primary", size = "md") {
  const base =
    "inline-flex items-center justify-center gap-2 font-semibold rounded-full transition-all active:scale-[0.98] whitespace-nowrap disabled:opacity-60 disabled:pointer-events-none";
  const sizes = {
    sm: "h-9 px-3.5 text-[13px]",
    md: "h-11 px-5 text-sm",
    lg: "h-12 px-7 text-sm",
  };
  const variants = {
    primary: "bg-brand text-white shadow-md shadow-brand/20 hover:bg-brandnavy",
    success: "bg-vitagreen text-white shadow-md shadow-vitagreen/25 hover:bg-greenink",
    outline: "border border-slate-200 bg-white text-ink hover:bg-slate-50 shadow-sm",
    ghost: "text-ink/70 hover:bg-slate-100 hover:text-ink",
    danger: "bg-white text-red-600 border border-red-200 hover:bg-red-50",
    subtle: "bg-brandsky text-brand hover:bg-brand/10",
  };
  return `${base} ${sizes[size]} ${variants[variant]}`;
}

export const STATUS_COLORS = {
  Pendiente: "bg-amber-50 text-amber-700 ring-1 ring-amber-200",
  Confirmada: "bg-sky-50 text-sky-700 ring-1 ring-sky-200",
  "En espera": "bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200",
  "En consulta": "bg-violet-50 text-violet-700 ring-1 ring-violet-200",
  Completada: "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200",
  Cancelada: "bg-rose-50 text-rose-700 ring-1 ring-rose-200",
  "No asistió": "bg-slate-100 text-slate-600 ring-1 ring-slate-200",
  Disponible: "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200",
  Ocupado: "bg-amber-50 text-amber-700 ring-1 ring-amber-200",
  Ausente: "bg-rose-50 text-rose-700 ring-1 ring-rose-200",
  Bloqueado: "bg-slate-100 text-slate-600 ring-1 ring-slate-300",
  Activo: "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200",
  Inactivo: "bg-slate-100 text-slate-500 ring-1 ring-slate-200",
  Publicado: "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200",
  Borrador: "bg-amber-50 text-amber-700 ring-1 ring-amber-200",
  Vacaciones: "bg-sky-50 text-sky-700 ring-1 ring-sky-200",
  Feriado: "bg-rose-50 text-rose-700 ring-1 ring-rose-200",
};

export function formatDate(iso, opts) {
  if (!iso) return "—";
  return new Date(iso).toLocaleDateString("es-ES", opts || { day: "2-digit", month: "short", year: "numeric" });
}

export function formatCurrency(n) {
  if (n == null) return "—";
  return new Intl.NumberFormat("es-DO", { style: "currency", currency: "DOP", maximumFractionDigits: 0 }).format(n);
}