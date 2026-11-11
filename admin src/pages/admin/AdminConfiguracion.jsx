import React, { useState } from "react";
import { Save, Upload, CalendarDays } from "lucide-react";
import { Switch } from "@/components/ui/switch";
import PageHeader from "@/components/admin/PageHeader";
import { Field, TextInput } from "@/components/admin/FormDialog";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { btn } from "@/components/admin/ui";
import { useToast } from "@/components/ui/use-toast";

const DIAS = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

export default function AdminConfiguracion() {
  const { toast } = useToast();
  const [laborables, setLaborables] = useState({ Lunes: true, Martes: true, Miércoles: true, Jueves: true, Viernes: true, Sábado: false, Domingo: false });
  const save = () => toast({ title: "Configuración guardada" });

  return (
    <div>
      <PageHeader
        title="Configuración"
        description="Preferencias generales del centro médico."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Configuración" }]}
        actions={<button className={btn("primary")} onClick={save}><Save className="w-4 h-4" /> Guardar cambios</button>}
      />
      <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <Tabs defaultValue="general">
          <TabsList className="w-full justify-start overflow-x-auto mb-5">
            {["General", "Citas", "Disponibilidad", "Contacto", "Estados"].map((t) => <TabsTrigger key={t} value={t.toLowerCase()}>{t}</TabsTrigger>)}
          </TabsList>

          <TabsContent value="general" className="max-w-2xl space-y-4">
            <Field label="Nombre del centro médico" required><TextInput defaultValue="Centro Médico Oriental" /></Field>
            <Field label="Logo"><div className="flex items-center gap-3"><span className="grid place-items-center w-12 h-12 rounded-xl bg-brand text-white font-bold">CMO</span><button className={btn("outline", "sm")}><Upload className="w-4 h-4" /> Cambiar logo</button></div></Field>
            <Field label="Dirección"><TextInput defaultValue="Av. 27 de Febrero #100, Santiago" /></Field>
            <div className="grid grid-cols-2 gap-3"><Field label="Teléfono"><TextInput defaultValue="+1 809 555 0123" /></Field><Field label="Correo"><TextInput type="email" defaultValue="contacto@cmo.do" /></Field></div>
          </TabsContent>

          <TabsContent value="citas" className="max-w-2xl space-y-4">
            <div className="grid grid-cols-2 gap-3">
              <Field label="Duración predeterminada (min)" required><TextInput type="number" defaultValue="30" /></Field>
              <Field label="Tiempo mínimo para reservar (horas)"><TextInput type="number" defaultValue="2" /></Field>
            </div>
            <Field label="Tiempo máximo de anticipación (días)"><TextInput type="number" defaultValue="90" /></Field>
            <Field label="Política de cancelación"><TextInput defaultValue="Cancelación gratuita hasta 4 horas antes de la cita." /></Field>
          </TabsContent>

          <TabsContent value="disponibilidad" className="max-w-2xl space-y-4">
            <Field label="Días laborables">
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
                {DIAS.map((d) => (
                  <div key={d} className="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2"><span className="text-[13px] font-medium text-ink/80">{d}</span><Switch checked={laborables[d]} onCheckedChange={(v) => setLaborables((s) => ({ ...s, [d]: v }))} /></div>
                ))}
              </div>
            </Field>
            <div className="grid grid-cols-2 gap-3"><Field label="Hora de inicio"><TextInput type="time" defaultValue="08:00" /></Field><Field label="Hora de cierre"><TextInput type="time" defaultValue="17:00" /></Field></div>
            <Field label="Días feriados" hint="Lista de fechas no laborables (AAA-MM-DD)"><TextInput defaultValue="2026-09-24, 2026-12-25" /></Field>
          </TabsContent>

          <TabsContent value="contacto" className="max-w-2xl space-y-4">
            <div className="grid grid-cols-2 gap-3"><Field label="Teléfono principal"><TextInput defaultValue="+1 809 555 0123" /></Field><Field label="WhatsApp"><TextInput defaultValue="+1 829 555 0145" /></Field></div>
            <Field label="Correo de contacto"><TextInput type="email" defaultValue="contacto@cmo.do" /></Field>
            <Field label="Dirección"><TextInput defaultValue="Av. 27 de Febrero #100, Santiago" /></Field>
          </TabsContent>

          <TabsContent value="estados" className="space-y-5 max-w-3xl">
            <div>
              <h4 className="font-heading font-bold text-ink/85 mb-2 flex items-center gap-2"><CalendarDays className="w-4 h-4 text-vitagreen" />Estados de citas</h4>
              <div className="flex flex-wrap gap-2">{["Pendiente", "Confirmada", "En espera", "En consulta", "Completada", "Cancelada", "No asistió"].map((s) => <span key={s} className="px-3 py-1.5 rounded-full bg-brandsky text-brand text-[13px] font-semibold">{s}</span>)}</div>
            </div>
            <div>
              <h4 className="font-heading font-bold text-ink/85 mb-2">Estados de pacientes</h4>
              <div className="flex flex-wrap gap-2">{["Activo", "Inactivo", "Suspendido"].map((s) => <span key={s} className="px-3 py-1.5 rounded-full bg-brandsky text-brand text-[13px] font-semibold">{s}</span>)}</div>
            </div>
            <div>
              <h4 className="font-heading font-bold text-ink/85 mb-2">Estados de médicos</h4>
              <div className="flex flex-wrap gap-2">{["Activo", "Inactivo", "Vacaciones", "Licencia"].map((s) => <span key={s} className="px-3 py-1.5 rounded-full bg-brandsky text-brand text-[13px] font-semibold">{s}</span>)}</div>
            </div>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
}