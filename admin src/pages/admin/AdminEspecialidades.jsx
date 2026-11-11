import React, { useState } from "react";
import { Plus, Pencil, Trash2, Shapes, ArrowLeft, Stethoscope } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { ESPECIALIDADES, MEDICOS, SERVICIOS } from "@/data/adminMock";

const COLUMNS = [
  { key: "name", label: "Especialidad" },
  { key: "doctores", label: "Nº de médicos" },
  { key: "servicios", label: "Nº de servicios" },
  { key: "status", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminEspecialidades() {
  const { toast } = useToast();
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);

  if (selected) {
    const e = ESPECIALIDADES.find((x) => x.id === selected.id);
    const docs = MEDICOS.filter((m) => m.specialties.includes(e.name));
    const servs = SERVICIOS.filter((s) => s.especialidad === e.name);
    return (
      <div>
        <button className={btn("ghost", "sm")} onClick={() => setSelected(null)}><ArrowLeft className="w-4 h-4" /> Volver a especialidades</button>
        <div className="mt-4 rounded-2xl bg-white border border-slate-200 p-6 shadow-sm flex items-center gap-4">
          <span className="grid place-items-center w-12 h-12 rounded-xl bg-brandsky text-brand"><Shapes className="w-6 h-6" /></span>
          <div>
            <h1 className="font-heading text-xl font-extrabold text-brand">{e.name}</h1>
            <div className="text-[13px] text-ink/55">{e.doctores} médicos · {e.servicios} servicios</div>
          </div>
          <span className="ml-auto"><StatusBadge status={e.status} /></span>
        </div>
        <div className="mt-5 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <Tabs defaultValue="info">
            <TabsList className="w-full justify-start mb-4"><TabsTrigger value="info">Información</TabsTrigger><TabsTrigger value="med">Médicos</TabsTrigger><TabsTrigger value="serv">Servicios</TabsTrigger></TabsList>
            <TabsContent value="info" className="text-ink/70 text-sm leading-relaxed max-w-2xl">Especialidad dedicada a la atención integral del paciente. Cuenta con profesionales certificados y servicios especializados según el protocolo del centro médico.</TabsContent>
            <TabsContent value="med">
              <AdminTable columns={[{ key: "name", label: "Médico" }, { key: "office", label: "Consultorio" }, { key: "availability", label: "Disponibilidad" }]} rows={docs} renderCell={(r, c) => c.key === "availability" ? <StatusBadge status={r.availability} /> : r[c.key]} />
            </TabsContent>
            <TabsContent value="serv">
              <AdminTable columns={[{ key: "name", label: "Servicio" }, { key: "duracion", label: "Duración" }]} rows={servs} renderCell={(r, c) => c.key === "duracion" ? `${r.duracion} min` : r[c.key]} />
            </TabsContent>
          </Tabs>
        </div>
      </div>
    );
  }

  return (
    <div>
      <PageHeader
        title="Especialidades"
        description="Catálogo de especialidades médicas."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Especialidades" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Crear especialidad</button>}
      />
      <AdminTable columns={COLUMNS} rows={ESPECIALIDADES} onRowClick={setSelected} renderCell={(r, c) => {
        if (c.key === "status") return <StatusBadge status={r.status} />;
        if (c.key === "acciones") return (
          <div className="flex items-center justify-end gap-1">
            <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand" onClick={(e) => { e.stopPropagation(); setSelected({ id: r.id }); }}><Pencil className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-rose-50 text-rose-600" onClick={(e) => e.stopPropagation()}><Trash2 className="w-4 h-4" /></button>
          </div>
        );
        return <span className="font-medium text-ink/85">{r[c.key]}</span>;
      }} />
      <FormDialog open={open} onOpenChange={setOpen} title="Crear especialidad" description="Define una nueva especialidad médica." saveLabel="Crear" onSave={() => toast({ title: "Especialidad creada" })}>
        <Field label="Nombre de la especialidad" required><TextInput placeholder="Ej. Neurología" /></Field>
        <Field label="Estado"><SelectInput><option>Activo</option><option>Inactivo</option></SelectInput></Field>
      </FormDialog>
    </div>
  );
}