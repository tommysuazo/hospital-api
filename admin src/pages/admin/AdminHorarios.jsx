import React, { useState } from "react";
import { Plus, CalendarClock, Slash } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusTabs from "@/components/admin/StatusTabs";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { MEDICOS } from "@/data/adminMock";
import StatusBadge from "@/components/admin/StatusBadge";

const FRANJAS = ["08:00", "09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00"];
const DIAS = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
const STATES = ["Disponible", "Cita", "Ocupado", "Bloqueado", "Ausente"];
const slotColor = (s) => ({
  Disponible: "bg-emerald-50 text-emerald-700 ring-emerald-200",
  Cita: "bg-brand/10 text-brand ring-brand/20",
  Ocupado: "bg-amber-50 text-amber-700 ring-amber-200",
  Bloqueado: "bg-slate-100 text-slate-500 ring-slate-200",
  Ausente: "bg-rose-50 text-rose-700 ring-rose-200",
})[s] || "bg-slate-50";

export default function AdminHorarios() {
  const { toast } = useToast();
  const [tab, setTab] = useState("medicos");
  const [open, setOpen] = useState(null);

  const tabs = [
    { key: "medicos", label: "Médicos" },
    { key: "sucursales", label: "Sucursales" },
    { key: "bloqueos", label: "Bloqueos" },
    { key: "ausencias", label: "Ausencias" },
    { key: "vacaciones", label: "Vacaciones" },
    { key: "feriados", label: "Feriados" },
  ];

  return (
    <div>
      <PageHeader
        title="Horarios y disponibilidad"
        description="Gestión visual de horarios, bloqueos y ausencias."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Horarios" }]}
        actions={
          <>
            <button className={btn("outline")} onClick={() => setOpen("configurar")}><CalendarClock className="w-4 h-4" /> Configurar horario</button>
            <button className={btn("outline")} onClick={() => setOpen("bloquear")}><Slash className="w-4 h-4" /> Bloquear horario</button>
            <button className={btn("primary")} onClick={() => setOpen("ausencia")}><Plus className="w-4 h-4" /> Registrar ausencia</button>
          </>
        }
      />

      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5">
        <StatusTabs tabs={tabs} active={tab} onChange={setTab} />
      </div>

      <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm overflow-x-auto">
        <div className="flex items-center justify-between mb-4">
          <h3 className="font-heading font-bold text-ink">Disponibilidad semanal</h3>
          <div className="flex items-center gap-3 text-[12px]">
            {STATES.map((s) => <span key={s} className={`inline-flex items-center gap-1.5 px-2 py-1 rounded-full ring-1 ${slotColor(s)}`}>{s}</span>)}
          </div>
        </div>
        <table className="w-full text-[12px] border-separate border-spacing-1 min-w-[760px]">
          <thead>
            <tr>
              <th className="text-left text-ink/50 font-semibold w-40 px-2 py-1">Médico</th>
              {FRANJAS.map((f) => <th key={f} className="text-ink/50 font-semibold px-2 py-1">{f}</th>)}
            </tr>
          </thead>
          <tbody>
            {MEDICOS.slice(0, 6).map((m, i) => (
              <tr key={m.id}>
                <td className="px-2 py-1.5">
                  <div className="font-semibold text-ink/85">{m.name}</div>
                  <div className="text-ink/40 text-[11px]">Cons. {m.office}</div>
                </td>
                {FRANJAS.map((_, fi) => {
                  const s = STATES[(fi + i) % STATES.length];
                  return <td key={fi} className="p-0"><div className={`h-11 rounded-md ring-1 grid place-items-center font-semibold ${slotColor(s)}`}>{s}</div></td>;
                })}
              </tr>
            ))}
          </tbody>
        </table>
        <div className="mt-4 grid sm:grid-cols-2 gap-3 text-[12px] text-ink/55">
          <p>Vista: <span className="font-semibold text-ink/80">Semanal</span> · Permite conmutar entre vista diaria y semanal, filtrar por sucursal y doctor.</p>
          <p className="sm:text-right">Haz clic sobre una celda para crear una cita o bloqueo en esa franja.</p>
        </div>
      </div>

      <FormDialog open={open === "bloquear"} onOpenChange={(v) => !v && setOpen(null)} title="Bloquear horario" description="Define un período no disponible." saveLabel="Bloquear" onSave={() => toast({ title: "Horario bloqueado" })}>
        <Field label="Médico" required><SelectInput>{MEDICOS.map((m) => <option key={m.id}>{m.name}</option>)}</SelectInput></Field>
        <div className="grid grid-cols-2 gap-3"><Field label="Desde"><TextInput type="datetime-local" /></Field><Field label="Hasta"><TextInput type="datetime-local" /></Field></div>
        <Field label="Motivo"><TextInput placeholder="Ej. Mantenimiento" /></Field>
      </FormDialog>
      <FormDialog open={open === "ausencia"} onOpenChange={(v) => !v && setOpen(null)} title="Registrar ausencia" saveLabel="Registrar" onSave={() => toast({ title: "Ausencia registrada" })}>
        <Field label="Médico" required><SelectInput>{MEDICOS.map((m) => <option key={m.id}>{m.name}</option>)}</SelectInput></Field>
        <Field label="Tipo"><SelectInput><option>Ausencia</option><option>Vacaciones</option><option>Licencia</option></SelectInput></Field>
        <div className="grid grid-cols-2 gap-3"><Field label="Inicio"><TextInput type="date" /></Field><Field label="Fin"><TextInput type="date" /></Field></div>
      </FormDialog>
      <FormDialog open={open === "configurar"} onOpenChange={(v) => !v && setOpen(null)} title="Configurar horario" description="Define el horario semanal del médico." size="xl" saveLabel="Guardar" onSave={() => toast({ title: "Horario configurado" })}>
        <Field label="Médico" required><SelectInput>{MEDICOS.map((m) => <option key={m.id}>{m.name}</option>)}</SelectInput></Field>
        <div className="overflow-x-auto">
          <table className="w-full text-[13px]">
            <thead><tr><th className="text-left text-ink/50 py-2">Día</th><th className="text-left text-ink/50 py-2">Entrada</th><th className="text-left text-ink/50 py-2">Salida</th><th className="text-left text-ink/50 py-2">Activo</th></tr></thead>
            <tbody>
              {DIAS.map((d) => (
                <tr key={d} className="border-t border-slate-100">
                  <td className="py-2 font-medium text-ink/80">{d}</td>
                  <td className="py-2"><TextInput type="time" className="h-9" defaultValue="08:00" /></td>
                  <td className="py-2"><TextInput type="time" className="h-9" defaultValue="16:00" /></td>
                  <td className="py-2"><input type="checkbox" defaultChecked={!["Sáb", "Dom"].includes(d)} /></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </FormDialog>
    </div>
  );
}