import React from "react";
import { Menu, Search, Bell, ChevronDown, LogOut, UserCircle, Settings } from "lucide-react";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from "@/components/ui/dropdown-menu";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { useAuth } from "@/lib/AuthContext";
import { base44 } from "@/api/base44Client";
import { Link } from "react-router-dom";

export default function Topbar({ onMenu }) {
  const { user } = useAuth() || {};
  const name = user?.full_name || user?.email || "Administrador";
  const role = user?.role || "Administrador";
  const initials = (name || "A").split(" ").map((s) => s[0]).slice(0, 2).join("").toUpperCase();

  return (
    <header className="sticky top-0 z-30 h-16 bg-white/85 backdrop-blur-xl border-b border-slate-200">
      <div className="h-full flex items-center gap-3 px-4 md:px-6">
        <button
          onClick={onMenu}
          className="lg:hidden grid place-items-center w-10 h-10 rounded-lg border border-slate-200 text-brand"
          aria-label="Abrir menú"
        >
          <Menu className="w-5 h-5" />
        </button>

        <div className="relative flex-1 max-w-md">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input
            type="text"
            placeholder="Buscar pacientes, médicos, citas…"
            className="w-full h-10 pl-9 pr-3 rounded-full bg-slate-100 border border-transparent text-sm text-ink placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-brand/30 focus:ring-2 focus:ring-brand/10"
          />
        </div>

        <div className="flex items-center gap-2 md:gap-4">
          <button className="relative grid place-items-center w-10 h-10 rounded-full hover:bg-slate-100 text-ink/70" aria-label="Notificaciones">
            <Bell className="w-5 h-5" />
            <span className="absolute top-2 right-2.5 w-2 h-2 rounded-full bg-vitagreen ring-2 ring-white" />
          </button>

          <DropdownMenu>
            <DropdownMenuTrigger className="flex items-center gap-2.5 rounded-full pl-1 pr-2 h-11 hover:bg-slate-100 transition-colors outline-none">
              <Avatar className="w-8 h-8">
                <AvatarFallback className="bg-brand text-white text-[13px] font-semibold">{initials}</AvatarFallback>
              </Avatar>
              <div className="hidden md:block text-left leading-tight">
                <div className="text-[13px] font-semibold text-ink max-w-[140px] truncate">{name}</div>
                <div className="text-[11px] text-ink/55 capitalize">{role}</div>
              </div>
              <ChevronDown className="hidden md:block w-4 h-4 text-ink/40" />
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
              <DropdownMenuLabel className="text-ink/50 text-[12px] uppercase tracking-wide">Cuenta</DropdownMenuLabel>
              <DropdownMenuItem className="cursor-pointer"><UserCircle className="w-4 h-4 mr-2" /> Mi perfil</DropdownMenuItem>
              <Link to="/admin/configuracion" className="block">
                <DropdownMenuItem className="cursor-pointer"><Settings className="w-4 h-4 mr-2" /> Configuración</DropdownMenuItem>
              </Link>
              <DropdownMenuSeparator />
              <DropdownMenuItem className="cursor-pointer text-red-600" onClick={() => base44.auth.logout("/")}>
                <LogOut className="w-4 h-4 mr-2" /> Cerrar sesión
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>
  );
}