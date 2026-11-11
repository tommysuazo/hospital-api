import React, { useState } from "react";
import { Plus, Pencil, KeyRound, Power } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { PERSONAL } from "@/data/adminMock";

const ROLES = ["Administrador", "Recepcionista", "Médico", "Laboratorio", "Gestión de pacientes"];
const COLUMNS = [
  { key: "usuario", label: "Usuario" },
  { key: "correo", label: "Correo" },
  { key: "rol", label: "Rol" },
  { key: "medico", label: "Médico relacionado" },
  { key: "ultimoAcceso", label: "Último acceso" },
  { key: "status", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminPersonal() {
  const { toast } = useToast();
  const [open, setOpen] = useState(false);
  return (
    <div>
      <PageHeader
        title="Personal"
        description="Usuarios con acceso al sistema."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Personal" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> Crear usuario</button>}
      />
      <AdminTable columns={COLUMNS} rows={PERSONAL} renderCell={(r, c) => {
        if (c.key === "rol") return <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-brandsky text-brand text-[12px] font-semibold">{r.rol}</span>;
        if (c.key === "status") return <StatusBadge status={r.status} />;
        if (c.key === "acciones") return (
          <div className="flex items-center justify-end gap-1">
            <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand" title="Editar"><Pencil className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-slate-100 text-ink/60" title="Asignar rol"><KeyRound className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-amber-50 text-amber-600" title="Activar/Desactivar"><Power className="w-4 h-4" /></button>
          </div>
        );
        return <span className="whitespace-nowrap">{r[c.key]}</span>;
      }} />
      <FormDialog open={open} onOpenChange={setOpen} title="Crear usuario" saveLabel="Crear" onSave={() => toast({ title: "Usuario creado" })}>
        <Field label="Nombre de usuario" required><TextInput /></Field>
        <Field label="Correo" required><TextInput type="email" /></Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Rol" required><SelectInput>{ROLES.map((r) => <option key={r}>{r}</option>)}</SelectInput></Field>
          <Field label="Médico relacionado"><SelectInput><option>Ninguno</option><option>Dra. María Rodríguez</option><option>Dr. Andrés Ferrer</option></SelectInput></Field>
        </div>
        <Field label="Contraseña temporal" required><TextInput type="password" /></Field>
      </FormDialog>
    </div>
  );
}