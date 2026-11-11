import React, { useState } from "react";
import { Eye, Pencil, Paperclip, Send } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusTabs from "@/components/admin/StatusTabs";
import AdminTable from "@/components/admin/AdminTable";
import StatusBadge from "@/components/admin/StatusBadge";
import { btn, formatDate } from "@/components/admin/ui";
import { cn } from "@/lib/utils";
import { RESULTADOS } from "@/data/adminMock";

const COLUMNS = [
  { key: "paciente", label: "Paciente" },
  { key: "tipo", label: "Tipo de resultado" },
  { key: "medico", label: "Médico" },
  { key: "fecha", label: "Fecha" },
  { key: "cita", label: "Cita relacionada" },
  { key: "estado", label: "Estado" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminResultados() {
  const [tab, setTab] = useState("pendientes");
  const FILTER = { todos: null, pendientes: "Pendiente", borradores: "Borrador", publicados: "Publicado" };
  const rows = FILTER[tab] == null ? RESULTADOS : RESULTADOS.filter((r) => r.estado === FILTER[tab]);
  const tabs = [
    { key: "todos", label: "Todos", count: RESULTADOS.length },
    { key: "pendientes", label: "Pendientes", count: RESULTADOS.filter((r) => r.estado === "Pendiente").length },
    { key: "borradores", label: "Borradores", count: RESULTADOS.filter((r) => r.estado === "Borrador").length },
    { key: "publicados", label: "Publicados", count: RESULTADOS.filter((r) => r.estado === "Publicado").length },
  ];

  return (
    <div>
      <PageHeader
        title="Resultados médicos"
        description="Gestión de resultados pendientes, borradores y publicados."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Resultados médicos" }]}
      />
      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5"><StatusTabs tabs={tabs} active={tab} onChange={setTab} /></div>
      <AdminTable columns={COLUMNS} rows={rows} renderCell={(r, c) => {
        if (c.key === "fecha") return formatDate(r.fecha);
        if (c.key === "estado") return <StatusBadge status={r.estado} />;
        if (c.key === "cita") return r.cita === "—" ? <span className="text-ink/40">—</span> : <span className="text-brand font-medium">#{r.cita.toUpperCase()}</span>;
        if (c.key === "acciones") return (
          <div className="flex items-center justify-end gap-1">
            <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand" title="Ver"><Eye className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-amber-50 text-amber-600" title="Editar"><Pencil className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-slate-100 text-ink/60" title="Adjuntar"><Paperclip className="w-4 h-4" /></button>
            {r.estado !== "Publicado" && <button className={cn(btn("success", "sm"))} title="Publicar"><Send className="w-3.5 h-3.5" /> Publicar</button>}
          </div>
        );
        return <span className="font-medium text-ink/85">{r[c.key]}</span>;
      }} />
    </div>
  );
}