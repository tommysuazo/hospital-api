import React, { useState } from "react";
import { Lock, Search } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import AdminTable from "@/components/admin/AdminTable";
import { TextInput, SelectInput } from "@/components/admin/FormDialog";
import { formatDate } from "@/components/admin/ui";
import { AUDIT } from "@/data/adminMock";

const COLUMNS = [
  { key: "fecha", label: "Fecha y hora" },
  { key: "usuario", label: "Usuario" },
  { key: "accion", label: "Acción" },
  { key: "modulo", label: "Módulo" },
  { key: "registro", label: "Registro afectado" },
  { key: "ip", label: "Dirección IP" },
];

export default function AdminAuditoria() {
  const [q, setQ] = useState("");
  const [modulo, setModulo] = useState("all");
  const modulos = ["all", ...Array.from(new Set(AUDIT.map((a) => a.modulo)))];
  const rows = AUDIT.filter((a) =>
    (q === "" || a.usuario.toLowerCase().includes(q.toLowerCase()) || a.accion.toLowerCase().includes(q.toLowerCase())) &&
    (modulo === "all" || a.modulo === modulo)
  );

  return (
    <div>
      <PageHeader
        title="Auditoría"
        description="Registro de actividades del sistema (solo lectura)."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Auditoría" }]}
        actions={<span className="inline-flex items-center gap-1.5 text-[13px] text-ink/45"><Lock className="w-4 h-4" /> Registro no editable</span>}
      />
      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5 flex flex-wrap items-center gap-2">
        <div className="relative flex-1 min-w-[220px]">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <TextInput value={q} onChange={(e) => setQ(e.target.value)} placeholder="Buscar por usuario o acción…" className="pl-9" />
        </div>
        <SelectInput value={modulo} onChange={(e) => setModulo(e.target.value)} className="w-auto h-11"><option value="all">Todos los módulos</option>{modulos.filter((m) => m !== "all").map((m) => <option key={m}>{m}</option>)}</SelectInput>
        <TextInput type="date" className="w-auto h-11" />
      </div>
      <AdminTable columns={COLUMNS} rows={rows} renderCell={(r, c) => {
        if (c.key === "accion") return <span className="inline-flex items-center gap-1.5"><span className="w-1.5 h-1.5 rounded-full bg-vitagreen" />{r.accion}</span>;
        if (c.key === "registro") return <span className="text-brand font-medium">{r.registro}</span>;
        return <span className="whitespace-nowrap">{r[c.key]}</span>;
      }} empty="No hay registros de auditoría para los filtros seleccionados." />
    </div>
  );
}