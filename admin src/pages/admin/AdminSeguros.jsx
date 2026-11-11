import React, { useState } from "react";
import { Plus, Pencil } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusTabs from "@/components/admin/StatusTabs";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { useToast } from "@/components/ui/use-toast";
import { btn } from "@/components/admin/ui";
import { ASEGURADORAS, PLANES, PACIENTES_ASEGURADOS } from "@/data/adminMock";

export default function AdminSeguros() {
  const { toast } = useToast();
  const [tab, setTab] = useState("aseguradoras");
  const [open, setOpen] = useState(false);
  const tabs = [
    { key: "aseguradoras", label: "Aseguradoras" },
    { key: "planes", label: "Planes" },
    { key: "pacientes", label: "Pacientes asegurados" },
  ];
  const titles = { aseguradoras: "Nueva aseguradora", planes: "Nuevo plan", pacientes: "Registrar seguro" };

  return (
    <div>
      <PageHeader
        title="Seguros"
        description="Aseguradoras, planes y pacientes asegurados."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Seguros" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Plus className="w-4 h-4" /> {titles[tab]}</button>}
      />
      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5"><StatusTabs tabs={tabs} active={tab} onChange={setTab} /></div>

      {tab === "aseguradoras" && (
        <AdminTable columns={[{ key: "name", label: "Aseguradora" }, { key: "planes", label: "Planes" }, { key: "telefono", label: "Teléfono" }, { key: "estado", label: "Estado" }, { key: "a", label: "", className: "text-right" }]} rows={ASEGURADORAS} renderCell={(r, c) => c.key === "estado" ? <StatusBadge status={r.estado} /> : c.key === "a" ? <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand"><Pencil className="w-4 h-4" /></button> : <span className="font-medium text-ink/85">{r[c.key]}</span>} />
      )}
      {tab === "planes" && (
        <AdminTable columns={[{ key: "aseguradora", label: "Aseguradora" }, { key: "plan", label: "Plan" }, { key: "cobertura", label: "Cobertura" }, { key: "estado", label: "Estado" }]} rows={PLANES} renderCell={(r, c) => c.key === "estado" ? <StatusBadge status={r.estado} /> : <span className="font-medium text-ink/85">{r[c.key]}</span>} />
      )}
      {tab === "pacientes" && (
        <AdminTable columns={[{ key: "paciente", label: "Paciente" }, { key: "aseguradora", label: "Aseguradora" }, { key: "plan", label: "Plan" }, { key: "afiliado", label: "Nº afiliado" }, { key: "titular", label: "Titular" }, { key: "vencimiento", label: "Vencimiento" }, { key: "estado", label: "Estado" }]} rows={PACIENTES_ASEGURADOS} renderCell={(r, c) => c.key === "estado" ? <StatusBadge status={r.estado} /> : <span className="whitespace-nowrap">{r[c.key]}</span>} />
      )}

      <FormDialog open={open} onOpenChange={setOpen} title={titles[tab]} saveLabel="Guardar" onSave={() => toast({ title: "Registro guardado" })}>
        {tab === "aseguradoras" && <><Field label="Nombre" required><TextInput placeholder="ARS X" /></Field><Field label="Teléfono"><TextInput placeholder="+1 809 ..." /></Field></>}
        {tab === "planes" && <><Field label="Aseguradora" required><SelectInput>{ASEGURADORAS.map((a) => <option key={a.id}>{a.name}</option>)}</SelectInput></Field><Field label="Plan" required><TextInput /></Field><Field label="Cobertura"><TextInput placeholder="80%" /></Field></>}
        {tab === "pacientes" && <><Field label="Paciente" required><SelectInput>{PACIENTES_ASEGURADOS.map((p) => <option key={p.id}>{p.paciente}</option>)}</SelectInput></Field><div className="grid grid-cols-2 gap-3"><Field label="Aseguradora"><SelectInput>{ASEGURADORAS.map((a) => <option key={a.id}>{a.name}</option>)}</SelectInput></Field><Field label="Plan"><SelectInput>{PLANES.map((p) => <option key={p.id}>{p.plan}</option>)}</SelectInput></Field></div><div className="grid grid-cols-2 gap-3"><Field label="Nº afiliado"><TextInput /></Field><Field label="Titular"><TextInput /></Field></div></>}
      </FormDialog>
    </div>
  );
}