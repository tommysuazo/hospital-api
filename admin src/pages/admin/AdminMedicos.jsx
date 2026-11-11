import React, { useState } from "react";
import { Plus, ArrowLeft, Calendar, ShieldCheck, Briefcase, Clock, DoorOpen, Phone, MapPin } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { Image } from "@/components/ui/image";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { MEDICOS, ESPECIALIDADES } from "@/data/adminMock";

export default function AdminMedicos() {
  const { toast } = useToast();
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);

  if (selected) {
    const m = MEDICOS.find((x) => x.id === selected.id);
    return (
      <div>
        <button className={btn("ghost", "sm")} onClick={() => setSelected(null)}><ArrowLeft className="w-4 h-4" /> Volver a médicos</button>
        <div className="mt-4 rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
          <div className="flex flex-col sm:flex-row sm:items-center gap-5">
            <Image src={m.photo} alt={m.name} className="w-20 h-20 rounded-2xl object-cover shrink-0" />
            <div className="flex-1">
              <h1 className="font-heading text-xl font-extrabold text-brand">{m.name}</h1>
              <div className="mt-1 text-[13px] text-vitagreen font-semibold">{m.specialties.join(" · ")}</div>
              <div className="mt-3 flex flex-wrap gap-4 text-[13px] text-ink/70">
                <span className="inline-flex items-center gap-1.5"><DoorOpen className="w-4 h-4 text-ink/40" />Consultorio {m.office}</span>
                <span className="inline-flex items-center gap-1.5"><Phone className="w-4 h-4 text-ink/40" />Ext. {m.extension}</span>
                <span className="inline-flex items-center gap-1.5"><MapPin className="w-4 h-4 text-ink/40" />{m.branch}</span>
              </div>
            </div>
            <div className="text-right">
              <StatusBadge status={m.availability} />
              <div className="mt-2"><StatusBadge status={m.status} /></div>
            </div>
          </div>
        </div>

        <div className="mt-5 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <Tabs defaultValue="resumen">
            <TabsList className="w-full justify-start overflow-x-auto mb-4">
              {["Resumen", "Perfil profesional", "Especialidades", "Servicios", "Horarios", "Disponibilidad", "Ausencias y vacaciones"].map((t) => (
                <TabsTrigger key={t} value={t.toLowerCase().split(" ")[0]}>{t}</TabsTrigger>
              ))}
            </TabsList>
            <TabsContent value="resumen" className="grid sm:grid-cols-2 gap-4">
              <Card icon={DoorOpen} label="Consultorio asignado" value={`${m.office} · Ext. ${m.extension}`} />
              <Card icon={MapPin} label="Sucursal" value={m.branch} />
              <Card icon={Calendar} label="Días laborables" value="Lun – Vie" />
              <Card icon={Clock} label="Horario" value="08:00 – 16:00" />
            </TabsContent>
            <TabsContent value="perfil" className="space-y-2 text-[14px] text-ink/75 leading-relaxed">
              <p>Médico especialista con amplia trayectoria en {m.specialties[0]}. Comprometido con la atención integral, humana y basada en evidencia.</p>
              <p>Registro médico: CMD-2026-{m.office}. Educación continua activa y participación en las guías clínicas del centro.</p>
            </TabsContent>
            <TabsContent value="especialidades" className="flex flex-wrap gap-2">{m.specialties.map((s) => <span key={s} className="px-3 py-1.5 rounded-full bg-brandsky text-brand text-[13px] font-semibold">{s}</span>)}</TabsContent>
            <TabsContent value="servicios" className="text-ink/55 text-sm">Consulta general y procedimientos de su especialidad. Detalle de servicios configurados en el módulo de Servicios.</TabsContent>
            <TabsContent value="horarios" className="text-ink/55 text-sm">Horario de atención configurable en el módulo de Horarios y disponibilidad.</TabsContent>
            <TabsContent value="disponibilidad" className="space-y-2">
              <div className="flex items-center justify-between rounded-lg border border-slate-100 p-3"><span className="text-ink/60 text-sm">Lunes</span><StatusBadge status="Disponible" /></div>
              <div className="flex items-center justify-between rounded-lg border border-slate-100 p-3"><span className="text-ink/60 text-sm">Martes</span><StatusBadge status="Disponible" /></div>
              <div className="flex items-center justify-between rounded-lg border border-slate-100 p-3"><span className="text-ink/60 text-sm">Miércoles</span><StatusBadge status="Ausente" /></div>
            </TabsContent>
            <TabsContent value="ausencias" className="space-y-2">
              <div className="rounded-lg border border-slate-100 p-3"><div className="text-[12px] text-ink/45">2026-09-10 — 2026-09-17</div><div className="text-[13px] font-medium text-ink/80">Vacaciones aprobadas</div></div>
            </TabsContent>
          </Tabs>
        </div>
      </div>
    );
  }

  return (
    <div>
      <PageHeader
        title="Médicos"
        description="Directorio de médicos y sus perfiles."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Médicos" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Registrar médico</button>}
      />

      <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {MEDICOS.map((m) => (
          <button key={m.id} onClick={() => setSelected({ id: m.id })} className="text-left rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div className="flex items-start gap-3">
              <Image src={m.photo} alt={m.name} className="w-14 h-14 rounded-xl object-cover shrink-0" />
              <div className="min-w-0 flex-1">
                <div className="font-heading font-bold text-ink/90 truncate">{m.name}</div>
                <div className="text-[12px] text-ink/55 truncate">{m.specialties.join(" · ")}</div>
                <div className="mt-1 text-[12px] text-ink/45">{m.branch}</div>
              </div>
            </div>
            <div className="mt-4 flex items-center justify-between text-[12px]">
              <span className="text-ink/55">Cons. <span className="font-semibold text-ink/75">{m.office}</span> · Ext. {m.extension}</span>
              <StatusBadge status={m.availability} />
            </div>
          </button>
        ))}
      </div>

      <FormDialog open={open} onOpenChange={setOpen} title="Registrar médico" description="Agrega un nuevo profesional al centro." saveLabel="Registrar" onSave={() => toast({ title: "Médico registrado", description: "El médico fue creado correctamente." })}>
        <Field label="Nombre completo" required><TextInput placeholder="Dr(a). Nombres y apellidos" /></Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Especialidad" required><SelectInput>{ESPECIALIDADES.map((e) => <option key={e.id}>{e.name}</option>)}</SelectInput></Field>
          <Field label="Sucursal"><SelectInput><option>Centro Médico Principal</option><option>Sucursal Norte</option></SelectInput></Field>
        </div>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Consultorio"><TextInput placeholder="Ej. 204" /></Field>
          <Field label="Extensión"><TextInput placeholder="Ej. 1204" /></Field>
        </div>
      </FormDialog>
    </div>
  );
}

function Card({ icon: Icon, label, value }) {
  return (
    <div className="rounded-xl border border-slate-100 p-4 bg-slate-50/40">
      <div className="flex items-center gap-1.5 text-[12px] text-ink/45 font-semibold uppercase tracking-wide">{Icon && <Icon className="w-3.5 h-3.5" />}{label}</div>
      <div className="mt-1.5 font-semibold text-ink/85 text-[15px]">{value}</div>
    </div>
  );
}