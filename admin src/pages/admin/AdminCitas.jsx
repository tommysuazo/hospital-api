import React, { useMemo, useState } from "react";
import {
  Plus, Calendar as CalIcon, List, Eye, Pencil, CalendarClock, Trash2, X,
} from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusTabs from "@/components/admin/StatusTabs";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { CITAS, PACIENTES, MEDICOS, ESPECIALIDADES } from "@/data/adminMock";

const STATUS = ["Todas", "Pendientes", "Confirmadas", "En espera", "En consulta", "Completadas", "Canceladas", "No asistieron"];
const MAP = { Todas: "todas", Pendientes: "Pendiente", Confirmadas: "Confirmada", "En espera": "En espera", "En consulta": "En consulta", Completadas: "Completada", Canceladas: "Cancelada", "No asistieron": "No asistió" };
const COLUMNS = [
  { key: "paciente", label: "Paciente" },
  { key: "medico", label: "Médico" },
  { key: "especialidad", label: "Especialidad" },
  { key: "fecha", label: "Fecha" },
  { key: "hora", label: "Hora" },
  { key: "consultorio", label: "Consultorio" },
  { key: "estado", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];
const TABS = [
  { label: "Información", v: "info" },
  { label: "Paciente", v: "pacie" },
  { label: "Médico", v: "medi" },
  { label: "Historial", v: "hist" },
  { label: "Resultados", v: "resu" },
];

export default function AdminCitas() {
  const { toast } = useToast();
  const [tab, setTab] = useState("todas");
  const [view, setView] = useState("lista");
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);

  const filtered = useMemo(() => (tab === "todas" ? CITAS : CITAS.filter((c) => c.estado === MAP[tab])), [tab]);
  const tabs = STATUS.map((s) => ({ key: MAP[s], label: s, count: s === "Todas" ? CITAS.length : CITAS.filter((c) => c.estado === MAP[s]).length }));

  const slotColor = (s) => ({
    Disponible: "bg-emerald-50 text-emerald-700 ring-emerald-200",
    Cita: "bg-brand/10 text-brand ring-brand/20",
    Ocupado: "bg-amber-50 text-amber-700 ring-amber-200",
    Bloqueado: "bg-slate-100 text-slate-500 ring-slate-200",
    Ausente: "bg-rose-50 text-rose-700 ring-rose-200",
  })[s] || "bg-slate-50";
  const STATES = ["Disponible", "Cita", "Ocupado", "Bloqueado", "Ausente"];

  return (
    <div>
      <PageHeader
        title="Citas"
        description="Gestión de citas del centro médico."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Citas" }]}
        actions={
          <>
            <button className={btn("outline")} onClick={() => setView(view === "lista" ? "calendario" : "lista")}>
              {view === "lista" ? <><CalIcon className="w-4 h-4" /> Calendario</> : <><List className="w-4 h-4" /> Lista</>}
            </button>
            <button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Nueva cita</button>
          </>
        }
      />

      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5">
        <div className="flex flex-col xl:flex-row xl:items-center gap-4">
          <StatusTabs tabs={tabs} active={tab} onChange={setTab} />
          <div className="flex flex-wrap items-center gap-2 xl:ml-auto">
            <SelectInput className="h-9 w-auto"><option>Todos los médicos</option>{MEDICOS.map((m) => <option key={m.id}>{m.name}</option>)}</SelectInput>
            <SelectInput className="h-9 w-auto"><option>Todas las especialidades</option>{ESPECIALIDADES.map((e) => <option key={e.id}>{e.name}</option>)}</SelectInput>
            <SelectInput className="h-9 w-auto"><option>Todas las sucursales</option><option>Centro Médico Principal</option><option>Sucursal Norte</option></SelectInput>
            <TextInput type="date" className="h-9 w-auto" />
            <TextInput placeholder="Paciente…" className="h-9 w-44" />
          </div>
        </div>
      </div>

      {view === "lista" ? (
        <AdminTable
          columns={COLUMNS}
          rows={filtered}
          onRowClick={(r) => setSelected(r)}
          renderCell={(r, c) => {
            if (c.key === "estado") return <StatusBadge status={r.estado} />;
            if (c.key === "fecha") return <span className="whitespace-nowrap">{new Date(r.fecha).toLocaleDateString("es-ES", { day: "2-digit", month: "short" })}</span>;
            if (c.key === "acciones") return (
              <div className="flex items-center justify-end gap-1">
                <button title="Ver" className="p-1.5 rounded-lg hover:bg-brand/10 text-brand" onClick={(e) => { e.stopPropagation(); setSelected(r); }}><Eye className="w-4 h-4" /></button>
                <button title="Reprogramar" className="p-1.5 rounded-lg hover:bg-amber-50 text-amber-600" onClick={(e) => e.stopPropagation()}><CalendarClock className="w-4 h-4" /></button>
                <button title="Cancelar" className="p-1.5 rounded-lg hover:bg-rose-50 text-rose-600" onClick={(e) => e.stopPropagation()}><Trash2 className="w-4 h-4" /></button>
              </div>
            );
            return <span className="whitespace-nowrap">{r[c.key]}</span>;
          }}
        />
      ) : (
        <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm overflow-x-auto">
          <div className="flex items-center justify-between mb-3">
            <h3 className="font-heading font-bold text-ink">Calendario semanal · 25 – 31 ago 2026</h3>
            <span className="text-[12px] text-ink/45">Semana actual</span>
          </div>
          <table className="w-full text-[12px] border-separate border-spacing-1">
            <thead>
              <tr>
                <th className="text-left text-ink/50 font-semibold w-36 px-2 py-1">Médico</th>
                {["08:00", "09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00"].map((f) => <th key={f} className="text-ink/50 font-semibold px-2 py-1">{f}</th>)}
              </tr>
            </thead>
            <tbody>
              {MEDICOS.slice(0, 5).map((m, i) => (
                <tr key={m.id}>
                  <td className="px-2 py-1.5">
                    <div className="font-semibold text-ink/85 text-[12px]">{m.name}</div>
                    <div className="text-ink/40 text-[11px]">Cons. {m.office}</div>
                  </td>
                  {Array.from({ length: 8 }).map((_, fi) => {
                    const s = STATES[(fi + i) % STATES.length];
                    return <td key={fi} className="p-0"><div className={`h-12 rounded-md ring-1 grid place-items-center font-semibold ${slotColor(s)}`}>{s}</div></td>;
                  })}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      <FormDialog open={open} onOpenChange={setOpen} title="Nueva cita" description="Registra una cita en el sistema." saveLabel="Crear cita" onSave={() => toast({ title: "Cita creada", description: "La cita se registró correctamente." })}>
        <Field label="Paciente" required><SelectInput>{PACIENTES.map((p) => <option key={p.id}>{p.name}</option>)}</SelectInput></Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Médico" required><SelectInput>{MEDICOS.map((m) => <option key={m.id}>{m.name}</option>)}</SelectInput></Field>
          <Field label="Especialidad" required><SelectInput>{ESPECIALIDADES.map((e) => <option key={e.id}>{e.name}</option>)}</SelectInput></Field>
        </div>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Fecha" required><TextInput type="date" /></Field>
          <Field label="Hora" required><TextInput type="time" /></Field>
        </div>
        <Field label="Consultorio"><TextInput placeholder="Ej. 204" /></Field>
      </FormDialog>

      {selected && (
        <div className="fixed inset-0 z-50 flex justify-end">
          <div className="absolute inset-0 bg-ink/40" onClick={() => setSelected(null)} />
          <div className="relative w-full max-w-2xl bg-white shadow-2xl h-full overflow-y-auto">
            <div className="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between">
              <div>
                <div className="text-[12px] text-ink/45">Cita #{selected.id.toUpperCase()}</div>
                <h2 className="font-heading text-lg font-bold text-brand">{selected.paciente}</h2>
              </div>
              <button className="p-2 rounded-lg hover:bg-slate-100" onClick={() => setSelected(null)}><X className="w-5 h-5" /></button>
            </div>
            <div className="px-6 py-5">
              <Tabs defaultValue="info">
                <TabsList className="w-full justify-start overflow-x-auto mb-4">
                  {TABS.map((t) => <TabsTrigger key={t.v} value={t.v}>{t.label}</TabsTrigger>)}
                </TabsList>
                <TabsContent value="info" className="space-y-3">
                  <Row label="Especialidad" value={selected.especialidad} />
                  <Row label="Fecha" value={new Date(selected.fecha).toLocaleDateString("es-ES", { weekday: "long", day: "numeric", month: "long" })} />
                  <Row label="Hora" value={selected.hora} />
                  <Row label="Consultorio" value={selected.consultorio} />
                  <Row label="Sucursal" value={selected.sucursal} />
                  <Row label="Estado" value={<StatusBadge status={selected.estado} />} />
                </TabsContent>
                <TabsContent value="pacie" className="space-y-3">
                  <Row label="Nombre" value={selected.paciente} />
                  <Row label="Teléfono" value="+1 809 555 0101" />
                  <Row label="Seguro" value="ARS Humano · Básico" />
                </TabsContent>
                <TabsContent value="medi" className="space-y-3">
                  <Row label="Médico" value={selected.medico} />
                  <Row label="Consultorio" value={selected.consultorio} />
                  <Row label="Extensión" value={`1${selected.consultorio}`} />
                </TabsContent>
                <TabsContent value="hist" className="space-y-3">
                  <div className="rounded-lg border border-slate-100 p-3"><div className="text-[12px] text-ink/45">2026-08-20 10:12</div><div className="text-[13px] text-ink/75">Cita reprogramada de 08:00 a {selected.hora}</div></div>
                  <div className="rounded-lg border border-slate-100 p-3"><div className="text-[12px] text-ink/45">2026-08-19 16:40</div><div className="text-[13px] text-ink/75">Estado cambiado a Confirmada</div></div>
                </TabsContent>
                <TabsContent value="resu" className="space-y-3"><p className="text-ink/50 text-sm">Sin resultados vinculados.</p></TabsContent>
              </Tabs>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

function Row({ label, value }) {
  return (
    <div className="flex items-center justify-between border-b border-slate-50 pb-2.5">
      <span className="text-[13px] text-ink/50">{label}</span>
      <span className="text-[13px] font-medium text-ink/85 text-right">{value}</span>
    </div>
  );
}