import React, { useState } from "react";
import { Building2, DoorOpen, Phone, Pencil, ChevronRight, ChevronDown, Layers } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatusBadge from "@/components/admin/StatusBadge";
import { cn } from "@/lib/utils";
import { SUCURSALES } from "@/data/adminMock";

export default function AdminInstalaciones() {
  const [expanded, setExpanded] = useState({ suc1: true });
  const [tab, setTab] = useState("sucursales");

  const TABS = [
    { key: "sucursales", label: "Sucursales" },
    { key: "departamentos", label: "Departamentos" },
    { key: "consultorios", label: "Consultorios" },
    { key: "pisos", label: "Pisos / Áreas" },
  ];

  return (
    <div>
      <PageHeader
        title="Instalaciones"
        description="Jerarquía de sucursales, pisos, departamentos y consultorios."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Instalaciones" }]}
      />

      <div className="flex items-center gap-1.5 mb-4">
        {TABS.map((t) => (
          <button key={t.key} onClick={() => setTab(t.key)} className={cn("h-9 px-4 rounded-full text-[13px] font-semibold border transition-colors", tab === t.key ? "bg-brand text-white border-brand" : "bg-white text-ink/65 border-slate-200 hover:bg-slate-50")}>{t.label}</button>
        ))}
      </div>

      <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <ul className="space-y-3">
          {SUCURSALES.map((suc) => (
            <li key={suc.id} className="rounded-xl border border-slate-200 overflow-hidden">
              <button className="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50" onClick={() => setExpanded((x) => ({ ...x, [suc.id]: !x[suc.id] }))}>
                <span className="grid place-items-center w-9 h-9 rounded-lg bg-brand text-white"><Building2 className="w-5 h-5" /></span>
                <div className="flex-1 text-left">
                  <div className="font-heading font-bold text-ink/85">{suc.nombre}</div>
                  <div className="text-[12px] text-ink/50">{suc.direccion}</div>
                </div>
                {expanded[suc.id] ? <ChevronDown className="w-5 h-5 text-ink/40" /> : <ChevronRight className="w-5 h-5 text-ink/40" />}
              </button>

              {expanded[suc.id] && (
                <ul className="bg-slate-50/50 px-3 pb-3 space-y-2">
                  {suc.pisos.map((p) => (
                    <li key={p.id} className="rounded-lg border border-slate-200 bg-white overflow-hidden">
                      <div className="flex items-center gap-2 px-3 py-2 border-b border-slate-100">
                        <Layers className="w-4 h-4 text-vitagreen" />
                        <span className="font-semibold text-ink/80 text-[13px]">{p.nombre}</span>
                      </div>
                      <ul className="p-3 space-y-2">
                        {p.departamentos.map((d) => (
                          <li key={d.id} className="rounded-lg bg-slate-50/70 p-3">
                            <div className="flex items-center gap-2 mb-2">
                              <DoorOpen className="w-4 h-4 text-slate-400" />
                              <span className="font-semibold text-ink/80 text-[13px]">{d.nombre}</span>
                            </div>
                            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-2">
                              {d.consultorios.map((co) => (
                                <div key={co.id} className="rounded-lg border border-slate-200 bg-white p-3">
                                  <div className="flex items-center justify-between">
                                    <span className="font-heading font-bold text-brand">No. {co.numero}</span>
                                    <StatusBadge status={co.estado === "Mantenimiento" ? "Bloqueado" : "Activo"} />
                                  </div>
                                  <div className="mt-2 text-[12px] text-ink/60 space-y-1">
                                    <div className="flex items-center gap-1.5"><Phone className="w-3 h-3" /> Ext. {co.extension}</div>
                                    <div>Piso: {co.piso} · {co.departamento}</div>
                                    <div className="text-ink/45">Médico: <span className="font-medium text-ink/75">{co.medico}</span></div>
                                  </div>
                                </div>
                              ))}
                            </div>
                          </li>
                        ))}
                      </ul>
                    </li>
                  ))}
                </ul>
              )}
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}