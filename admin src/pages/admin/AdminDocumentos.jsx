import React, { useState } from "react";
import { Search, Upload, Download, Eye, Trash2 } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusTabs from "@/components/admin/StatusTabs";
import AdminTable from "@/components/admin/AdminTable";
import FormDialog, { Field, TextInput, SelectInput } from "@/components/admin/FormDialog";
import { useToast } from "@/components/ui/use-toast";
import { btn, formatDate } from "@/components/admin/ui";
import { DOCUMENTOS } from "@/data/adminMock";

const COLUMNS = [
  { key: "name", label: "Documento" },
  { key: "paciente", label: "Paciente" },
  { key: "categoria", label: "Categoría" },
  { key: "fecha", label: "Fecha" },
  { key: "subidoPor", label: "Subido por" },
  { key: "acciones", label: "Acciones", className: "text-right" },
];

export default function AdminDocumentos() {
  const { toast } = useToast();
  const [tab, setTab] = useState("todos");
  const [open, setOpen] = useState(false);
  const [q, setQ] = useState("");
  const cats = ["Identificación", "Tarjetas de seguro", "Consentimientos", "Autorizaciones", "Órdenes médicas", "Otros"];
  const tabs = [{ key: "todos", label: "Todos", count: DOCUMENTOS.length }, ...cats.map((c) => ({ key: c, label: c, count: DOCUMENTOS.filter((d) => d.categoria === c).length }))];
  const rows = DOCUMENTOS.filter((d) => (tab === "todos" || d.categoria === tab) && (q === "" || d.paciente.toLowerCase().includes(q.toLowerCase()) || d.name.toLowerCase().includes(q.toLowerCase())));

  return (
    <div>
      <PageHeader
        title="Documentos"
        description="Repositorio de documentos del expediente clínico."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Documentos" }]}
        actions={<button className={btn("primary")} onClick={() => setOpen(true)}><Upload className="w-4 h-4" /> Subir documento</button>}
      />
      <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm mb-5">
        <div className="flex flex-col lg:flex-row gap-3 lg:items-center">
          <StatusTabs tabs={tabs} active={tab} onChange={setTab} />
          <div className="relative lg:ml-auto flex-1 max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <TextInput value={q} onChange={(e) => setQ(e.target.value)} placeholder="Buscar por paciente o documento…" className="pl-9" />
          </div>
        </div>
      </div>
      <AdminTable columns={COLUMNS} rows={rows} renderCell={(r, c) => {
        if (c.key === "fecha") return formatDate(r.fecha);
        if (c.key === "acciones") return (
          <div className="flex items-center justify-end gap-1">
            <button className="p-1.5 rounded-lg hover:bg-brand/10 text-brand" title="Ver"><Eye className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-slate-100 text-ink/60" title="Descargar"><Download className="w-4 h-4" /></button>
            <button className="p-1.5 rounded-lg hover:bg-rose-50 text-rose-600" title="Eliminar"><Trash2 className="w-4 h-4" /></button>
          </div>
        );
        return <span className="font-medium text-ink/85">{r[c.key]}</span>;
      }} />
      <FormDialog open={open} onOpenChange={setOpen} title="Subir documento" saveLabel="Subir" onSave={() => toast({ title: "Documento subido" })}>
        <Field label="Paciente" required><SelectInput><option>Laura Gómez</option><option>Carlos Peréz</option><option>Marta Suero</option></SelectInput></Field>
        <Field label="Categoría" required><SelectInput>{cats.map((c) => <option key={c}>{c}</option>)}</SelectInput></Field>
        <Field label="Archivo"><div className="rounded-lg border-2 border-dashed border-slate-200 p-6 text-center text-ink/50 text-sm">Arrastra el archivo o haz clic para seleccionar</div></Field>
      </FormDialog>
    </div>
  );
}