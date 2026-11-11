import React, { useState } from "react";
import { Plus, Pencil, Users } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { useToast } from "@/components/ui/use-toast";
import { btn, formatCurrency } from "@/components/admin/ui";
import { SERVICIOS, ESPECIALIDADES } from "@/data/adminMock";

const COLUMNS = [
  { key: "name", label: "Servicio" },
  { key: "especialidad", label: "Especialidad" },
  { key: "duracion", label: "Duración" },
  { key: "precio", label: "Precio" },
  { key: "doctores", label: "Médicos disponibles" },
  { key: "status", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminServicios() {
  const { toast } = useToast();
  const [open, setOpen] = useState(false);
  return (
    <div>
      <PageHeader
        title="Servicios médicos"
        description="Servicios ofrecidos por el centro y sus médicos."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Servicios" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Nuevo servicio</button>}
      />
      <AdminTable columns={COLUMNS} rows={SERVICIOS} renderCell={(r, c) => {
        if (c.key === "duracion") return `${r.duracion} min`;
        if (c.key === "precio") return r.precio != null ? <span className="font-semibold text-ink/85">{formatCurrency(r.precio)}</span> : <span className="text-ink/40">—</span>;
        if (c.key === "doctores") return <span className="inline-flex items-center gap-1.5 text-ink/70"><Users className="w-3.5 h-3.5 text-slate-400" />{r.doctores.length}</span>;
        if (c.key === "status") return <StatusBadge status={r.status} />;
        if (c.key === "acciones") return <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand"><Pencil className="w-4 h-4" /></button>;
        return <span className="font-medium text-ink/85">{r[c.key]}</span>;
      }} />
      <FormDialog open={open} onOpenChange={setOpen} title="Nuevo servicio" description="Registra un servicio médico. El precio es opcional." saveLabel="Crear" onSave={() => toast({ title: "Servicio creado" })}>
        <Field label="Nombre del servicio" required><TextInput placeholder="Ej. Consulta pediátrica" /></Field>
        <Field label="Especialidad" required><SelectInput>{ESPECIALIDADES.map((e) => <option key={e.id}>{e.name}</option>)}</SelectInput></Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Duración (min)"><TextInput type="number" placeholder="30" /></Field>
          <Field label="Precio (opcional)" hint="Dejar en blanco si no aplica"><TextInput type="number" placeholder="2500" /></Field>
        </div>
        <Field label="Asignar médicos"><SelectInput multiple className="h-28">{["Dra. María Rodríguez", "Dr. Andrés Ferrer", "Dra. Daniela Ortiz", "Dra. Sofía Vega", "Dr. Ricardo Mota", "Dr. Pablo Ríos"].map((n) => <option key={n}>{n}</option>)}</SelectInput></Field>
      </FormDialog>
    </div>
  );
}