import React, { useState } from "react";
import { Plus, Pencil } from "lucide-react";
import { Switch } from "@/components/ui/switch";
import { cn } from "@/lib/utils";
import PageHeader from "@/components/admin/PageHeader";
import { btn } from "@/components/admin/ui";
import { useToast } from "@/components/ui/use-toast";

const MODULES = ["Citas", "Pacientes", "Médicos", "Resultados médicos", "Seguros", "Documentos", "Configuración"];
const PERMS = ["Ver", "Crear", "Editar", "Eliminar"];
const ROLES = [
  { name: "Administrador", color: "bg-brand text-white", perms: { Citas: [1, 1, 1, 1], Pacientes: [1, 1, 1, 1], Médicos: [1, 1, 1, 1], "Resultados médicos": [1, 1, 1, 1], Seguros: [1, 1, 1, 1], Documentos: [1, 1, 1, 1], Configuración: [1, 1, 1, 1] } },
  { name: "Recepción", color: "bg-sky-100 text-sky-700", perms: { Citas: [1, 1, 1, 0], Pacientes: [1, 1, 1, 0], Médicos: [1, 0, 0, 0], "Resultados médicos": [1, 0, 0, 0], Seguros: [1, 1, 0, 0], Documentos: [1, 1, 1, 0], Configuración: [0, 0, 0, 0] } },
  { name: "Médico", color: "bg-emerald-100 text-emerald-700", perms: { Citas: [1, 0, 1, 0], Pacientes: [1, 0, 1, 0], Médicos: [1, 0, 0, 0], "Resultados médicos": [1, 1, 1, 0], Seguros: [1, 0, 0, 0], Documentos: [1, 1, 0, 0], Configuración: [0, 0, 0, 0] } },
  { name: "Laboratorio", color: "bg-amber-100 text-amber-700", perms: { Citas: [1, 0, 0, 0], Pacientes: [1, 0, 0, 0], Médicos: [1, 0, 0, 0], "Resultados médicos": [1, 1, 1, 0], Seguros: [1, 0, 0, 0], Documentos: [1, 1, 0, 0], Configuración: [0, 0, 0, 0] } },
];

export default function AdminRoles() {
  const { toast } = useToast();
  const [active, setActive] = useState(ROLES[0].name);
  const role = ROLES.find((r) => r.name === active);
  const [perms, setPerms] = useState(role.perms);
  const toggle = (m, i) => setPerms((p) => ({ ...p, [m]: p[m].map((v, idx) => idx === i ? 1 - v : v) }));

  return (
    <div>
      <PageHeader
        title="Roles y permisos"
        description="Define roles y la matriz de permisos por módulo."
        breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Roles y permisos" }]}
        actions={<button className={btn("primary")} onClick={() => toast({ title: "Rol creado" })}><Plus className="w-4 h-4" /> Crear rol</button>}
      />

      <div className="grid lg:grid-cols-[260px_1fr] gap-5">
        <div className="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm h-fit">
          <h3 className="font-heading font-bold text-ink/85 mb-3">Roles</h3>
          <ul className="space-y-1.5">
            {ROLES.map((r) => (
              <li key={r.name}>
                <button onClick={() => { setActive(r.name); setPerms(r.perms); }} className={cn("w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[14px] font-semibold transition-colors", active === r.name ? "bg-brand/5 text-brand" : "text-ink/70 hover:bg-slate-50")}>
                  {r.name}
                  <span className={cn("px-2 py-0.5 rounded-full text-[11px]", r.color)}>{MODULES.length * 4 - Object.values(r.perms).flat().filter((v) => !v).length}/{MODULES.length * 4}</span>
                </button>
              </li>
            ))}
          </ul>
        </div>

        <div className="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h3 className="font-heading font-bold text-ink/85">Matriz de permisos · {role.name}</h3>
              <p className="text-[12px] text-ink/45">Activa o desactiva los permisos por módulo.</p>
            </div>
            <button className={btn("success", "sm")} onClick={() => toast({ title: "Permisos guardados" })}><Pencil className="w-3.5 h-3.5" /> Guardar</button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead className="bg-slate-50 border-b border-slate-200">
                <tr><th className="text-left font-semibold text-ink/60 px-5 py-3">Módulo</th>{PERMS.map((p) => <th key={p} className="text-center font-semibold text-ink/60 px-4 py-3">{p}</th>)}</tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {MODULES.map((m) => (
                  <tr key={m} className="hover:bg-slate-50/50">
                    <td className="px-5 py-3 font-medium text-ink/85">{m}</td>
                    {perms[m].map((v, i) => (
                      <td key={i} className="px-4 py-3 text-center"><div className="flex justify-center"><Switch checked={!!v} onCheckedChange={() => toggle(m, i)} /></div></td>
                    ))}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}