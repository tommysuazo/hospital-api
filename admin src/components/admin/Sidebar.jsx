import React from "react";
import {
  LayoutDashboard, CalendarDays, Users, Stethoscope, FlaskConical, CalendarClock,
  Shapes, Activity, Building2, ShieldCheck, FolderOpen, UserCog, KeyRound,
  ScrollText, Settings,
} from "lucide-react";
import { NavLink } from "react-router-dom";

const GROUPS = [
  {
    title: "Operación",
    items: [
      { label: "Dashboard", to: "/admin", icon: LayoutDashboard, end: true },
      { label: "Citas", to: "/admin/citas", icon: CalendarDays },
      { label: "Pacientes", to: "/admin/pacientes", icon: Users },
      { label: "Médicos", to: "/admin/medicos", icon: Stethoscope },
      { label: "Resultados médicos", to: "/admin/resultados", icon: FlaskConical },
      { label: "Horarios", to: "/admin/horarios", icon: CalendarClock },
    ],
  },
  {
    title: "Recursos",
    items: [
      { label: "Especialidades", to: "/admin/especialidades", icon: Shapes },
      { label: "Servicios", to: "/admin/servicios", icon: Activity },
      { label: "Instalaciones", to: "/admin/instalaciones", icon: Building2 },
      { label: "Seguros", to: "/admin/seguros", icon: ShieldCheck },
      { label: "Documentos", to: "/admin/documentos", icon: FolderOpen },
    ],
  },
  {
    title: "Sistema",
    items: [
      { label: "Personal", to: "/admin/personal", icon: UserCog },
      { label: "Roles y permisos", to: "/admin/roles", icon: KeyRound },
      { label: "Auditoría", to: "/admin/auditoria", icon: ScrollText },
      { label: "Configuración", to: "/admin/configuracion", icon: Settings },
    ],
  },
];

export default function Sidebar({ open, onClose }) {
  return (
    <>
      {open && (
        <div className="fixed inset-0 z-40 bg-ink/40 lg:hidden" onClick={onClose} />
      )}
      <aside
        className={`fixed inset-y-0 left-0 z-50 w-64 bg-brand text-white flex flex-col transition-transform duration-300 lg:translate-x-0 ${
          open ? "translate-x-0" : "-translate-x-full"
        }`}
      >
        <div className="h-16 flex items-center gap-2.5 px-5 border-b border-white/10 shrink-0">
          <span className="grid place-items-center w-9 h-9 rounded-xl bg-vitagreen text-white shadow-md">
            <Stethoscope className="w-5 h-5" />
          </span>
          <div className="leading-tight">
            <div className="font-heading font-extrabold text-[15px] tracking-tight">Centro Médico</div>
            <div className="font-heading font-semibold text-vitagreen text-[11px] tracking-[0.18em] uppercase">
              Oriental · Admin
            </div>
          </div>
        </div>

        <nav className="flex-1 overflow-y-auto px-3 py-4 space-y-6">
          {GROUPS.map((g) => (
            <div key={g.title}>
              <p className="px-3 mb-2 text-[11px] font-semibold tracking-[0.16em] uppercase text-white/40">
                {g.title}
              </p>
              <ul className="space-y-0.5">
                {g.items.map((it) => {
                  const Icon = it.icon;
                  return (
                    <li key={it.to}>
                      <NavLink
                        to={it.to}
                        end={it.end}
                        onClick={onClose}
                        className={({ isActive }) =>
                          `flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-medium transition-colors ${
                            isActive
                              ? "bg-white/15 text-white shadow-inner"
                              : "text-white/70 hover:bg-white/10 hover:text-white"
                          }`
                        }
                      >
                        <Icon className="w-[18px] h-[18px] shrink-0" />
                        {it.label}
                      </NavLink>
                    </li>
                  );
                })}
              </ul>
            </div>
          ))}
        </nav>

        <div className="p-3 border-t border-white/10 shrink-0">
          <div className="rounded-lg bg-white/5 px-3 py-2.5 text-[12px] text-white/60">
            v1.0 · Portal administrativo
          </div>
        </div>
      </aside>
    </>
  );
}