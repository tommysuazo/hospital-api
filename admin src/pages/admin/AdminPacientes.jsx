import React, { useMemo, useState } from "react";
import { Plus, ArrowLeft, Search, ShieldCheck, CalendarDays, FlaskConical, Phone, Mail, Cake, User } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { useToast } from "@/components/ui/use-toast";
import { btn, formatDate } from "@/components/admin/ui";
import { PACIENTES, CITAS, DOCUMENTOS } from "@/data/adminMock";

const COLUMNS = [
  { key: "paciente", label: "Paciente" },
  { key: "documento", label: "Documento" },
  { key: "telefono", label: "Teléfono" },
  { key: "seguro", label: "Seguro" },
  { key: "ultimaCita", label: "Última cita" },
  { key: "proximaCita", label: "Próxima cita" },
  { key: "estado", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminPacientes() {
  const { toast } = useToast();
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);
  const [q, setQ] = useState("");
  const [seguro, setSeguro] = useState("all");
  const [estado, setEstado] = useState("all");

  const rows = useMemo(() => PACIENTES.map((p) => ({
    id: p.id, paciente: p.name, documento: p.document, telefono: p.phone, seguro: p.seguro,
    ultimaCita: formatDate(p.ultimaCita), proximaCita: formatDate(p.proximaCita), estado: p.status,
  })).filter((r) =>
    (q === "" || r.paciente.toLowerCase().includes(q.toLowerCase()) || r.documento.includes(q)) &&
    (seguro === "all" || r.seguro.includes(seguro)) &&
    (estado === "all" || r.estado === estado)
  ), [q, seguro, estado]);

  if (selected) {
    const p = PACIENTES.find((x) => x.id === selected.id);
    return (
      <div>
        <button className={btn("ghost", "sm")} onClick={() => setSelected(null)}><ArrowLeft className="w-4 h-4" /> Volver a pacientes</button>
        <div className="mt-4 rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
          <div className="flex flex-col sm:flex-row sm:items-center gap-5">
            <Avatar className="w-20 h-20"><AvatarFallback className="bg-brand text-white text-2xl font-bold">{p.name.split(" ").map((s) => s[0]).slice(0, 2).join("")}</AvatarFallback></Avatar>
            <div className="flex-1">
              <h1 className="font-heading text-xl font-extrabold text-brand">{p.name}</h1>
              <div className="mt-1 text-[13px] text-ink/55">ID de paciente: <span className="font-semibold text-ink/75">#{p.id.toUpperCase()}-2026</span></div>
              <div className="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 text-[13px] text-ink/70">
                <span className="inline-flex items-center gap-1.5"><Cake className="w-4 h-4 text-ink/40" />{formatDate(p.dob)}</span>
                <span className="inline-flex items-center gap-1.5"><User className="w-4 h-4 text-ink/40" />{p.sexo}</span>
                <span className="inline-flex items-center gap-1.5"><Phone className="w-4 h-4 text-ink/40" />{p.phone}</span>
                <span className="inline-flex items-center gap-1.5"><Mail className="w-4 h-4 text-ink/40" />{p.email}</span>
              </div>
            </div>
            <StatusBadge status={p.status} />
          </div>
        </div>

        <div className="mt-5 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <Tabs defaultValue="resumen">
            <TabsList className="w-full justify-start overflow-x-auto mb-4">
              {["Resumen", "Citas", "Resultados", "Documentos", "Seguros", "Contactos"].map((t) => (
                <TabsTrigger key={t} value={t.toLowerCase()}>{t}</TabsTrigger>
              ))}
            </TabsList>
            <TabsContent value="resumen" className="grid sm:grid-cols-3 gap-3">
              <InfoCard label="Última cita" value={formatDate(p.ultimaCita)} icon={CalendarDays} />
              <InfoCard label="Próxima cita" value={formatDate(p.proximaCita)} icon={CalendarDays} />
              <InfoCard label="Seguro" value={p.seguro} icon={ShieldCheck} />
            </TabsContent>
            <TabsContent value="citas">
              <AdminTable columns={[{ key: "fecha", label: "Fecha" }, { key: "medico", label: "Médico" }, { key: "estado", label: "Estado" }]} rows={CITAS.filter((c) => c.paciente === p.name)} renderCell={(r, c) => c.key === "estado" ? <StatusBadge status={r.estado} /> : <span>{c.key === "fecha" ? formatDate(r.fecha) + " · " + r.hora : r[c.key]}</span>} />
            </TabsContent>
            <TabsContent value="resultados"><Empty label="Sin resultados registrados." icon={FlaskConical} /></TabsContent>
            <TabsContent value="documentos">
              <AdminTable columns={[{ key: "name", label: "Documento" }, { key: "categoria", label: "Categoría" }, { key: "fecha", label: "Fecha" }]} rows={DOCUMENTOS.filter((d) => d.paciente === p.name)} renderCell={(r, c) => c.key === "fecha" ? formatDate(r.fecha) : r[c.key]} />
            </TabsContent>
            <TabsContent value="seguros">
              <div className="rounded-xl border border-slate-100 p-4 max-w-md">
                <div className="text-[12px] text-ink/45 uppercase tracking-wide">Aseguradora</div>
                <div className="font-semibold text-ink/85">{p.seguro}</div>
                <div className="mt-3 grid grid-cols-2 gap-3 text-[13px]">
                  <div><div className="text-ink/45">Número de afiliado</div><div className="font-medium">HUM-102030</div></div>
                  <div><div className="text-ink/45">Titular</div><div className="font-medium">{p.name}</div></div>
                  <div><div className="text-ink/45">Vigencia</div><div className="font-medium">2025-01-15 — 2026-12-31</div></div>
                  <div><div className="text-ink/45">Estado</div><StatusBadge status="Activo" /></div>
                </div>
              </div>
            </TabsContent>
            <TabsContent value="contactos">
              <div className="rounded-xl border border-slate-100 p-4 max-w-md">
                <div className="font-semibold text-ink/85 mb-2">Contacto de emergencia</div>
                <div className="text-[13px] text-ink/70">Ana Gómez · Madre · +1 809 555 0001</div>
              </div>
            </TabsContent>
          </Tabs>
        </div>
      </div>
    );
  }

  return (
    <div>
      <PageHeader
        title="Pacientes"
        description="Administración y perfiles de pacientes."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Pacientes" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Registrar paciente</button>}
      />

      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5 flex flex-wrap items-center gap-2">
        <div className="relative flex-1 min-w-[220px]">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <TextInput value={q} onChange={(e) => setQ(e.target.value)} placeholder="Buscar por nombre o documento…" className="pl-9" />
        </div>
        <SelectInput value={seguro} onChange={(e) => setSeguro(e.target.value)} className="w-auto h-11"><option value="all">Todos los seguros</option><option value="Humano">ARS Humano</option><option value="Mapfre">Mapfre</option><option value="Sin seguro">Sin seguro</option></SelectInput>
        <SelectInput value={estado} onChange={(e) => setEstado(e.target.value)} className="w-auto h-11"><option value="all">Todos los estados</option><option value="Activo">Activo</option><option value="Inactivo">Inactivo</option></SelectInput>
      </div>

      <AdminTable columns={COLUMNS} rows={rows} onRowClick={setSelected} renderCell={(r, c) => {
        if (c.key === "estado") return <StatusBadge status={r.estado} />;
        return <span className="whitespace-nowrap">{r[c.key]}</span>;
      }} />

      <FormDialog open={open} onOpenChange={setOpen} title="Registrar paciente" description="Crea un nuevo expediente de paciente." saveLabel="Registrar" onSave={() => toast({ title: "Paciente registrado", description: "El paciente fue creado correctamente." })}>
        <Field label="Nombre completo" required><TextInput placeholder="Nombres y apellidos" /></Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Documento" required><TextInput placeholder="000-0000000-0" /></Field>
          <Field label="Fecha de nacimiento"><TextInput type="date" /></Field>
        </div>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Teléfono"><TextInput placeholder="+1 809 000-0000" /></Field>
          <Field label="Correo"><TextInput type="email" placeholder="correo@mail.com" /></Field>
        </div>
        <Field label="Seguro"><SelectInput><option>Sin seguro</option><option>ARS Humano</option><option>Mapfre Salud</option><option>ARS Palic</option></SelectInput></Field>
      </FormDialog>
    </div>
  );
}

function InfoCard({ label, value, icon: Icon }) {
  return (
    <div className="rounded-xl border border-slate-100 p-4 bg-slate-50/40">
      <div className="flex items-center gap-1.5 text-[12px] text-ink/45 font-semibold uppercase tracking-wide">{Icon && <Icon className="w-3.5 h-3.5" />}{label}</div>
      <div className="mt-1.5 font-semibold text-ink/85 text-[15px]">{value}</div>
    </div>
  );
}
function Empty({ label, icon: Icon }) {
  return <div className="py-10 text-center text-ink/45 text-sm flex flex-col items-center gap-2"><Icon className="w-7 h-7 text-ink/30" />{label}</div>;
}