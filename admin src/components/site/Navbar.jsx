import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { Menu, X, HeartPulse, LogIn } from "lucide-react";

const NAV_ITEMS = [
  { label: "Inicio", href: "/#inicio" },
  { label: "Doctores", to: "/doctores" },
  { label: "Especialidades", to: "/especialidades" },
  { label: "Nosotros", to: "/nosotros" },
  { label: "Contacto", to: "/contacto" },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 12);
    onScroll();
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <header
      className="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-white/85 backdrop-blur-xl border-b border-slate-200/70 shadow-[0_4px_24px_-12px_rgba(39,68,129,0.25)]"
    >
      <nav className="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between" style={{ height: "4.75rem" }}>
        <a href="#inicio" className="flex items-center gap-2.5 shrink-0">
          <span className="grid place-items-center w-10 h-10 rounded-xl bg-brand text-white shadow-md shadow-brand/30">
            <HeartPulse className="w-5 h-5" />
          </span>
          <span className="leading-tight">
            <span className="block font-heading font-extrabold text-brand text-[15px] tracking-tight">Centro Médico</span>
            <span className="block font-heading font-semibold text-vitagreen text-[12px] -mt-0.5 tracking-[0.18em] uppercase">Oriental</span>
          </span>
        </a>

        <ul className="hidden lg:flex items-center gap-1">
          {NAV_ITEMS.map((item) => {
            const cls = "px-3.5 py-2 rounded-lg text-[15px] font-medium text-ink/80 hover:text-brand hover:bg-brandsky/60 transition-colors";
            return (
              <li key={item.to || item.href}>
                {item.to ? (
                  <Link to={item.to} className={cls}>{item.label}</Link>
                ) : (
                  <a href={item.href} className={cls}>{item.label}</a>
                )}
              </li>
            );
          })}
        </ul>

        <div className="hidden lg:flex items-center gap-3">
          <Link
            to="/login"
            className="inline-flex items-center gap-2 px-5 h-11 rounded-full bg-brand text-white text-[15px] font-semibold shadow-md shadow-brand/25 hover:bg-brandnavy hover:shadow-lg hover:shadow-brand/30 active:scale-[0.98] transition-all"
          >
            <LogIn className="w-4 h-4" />
            Iniciar Sesión
          </Link>
        </div>

        <button
          aria-label="Abrir menú"
          className="lg:hidden grid place-items-center w-11 h-11 rounded-xl border border-slate-200 bg-white/70 backdrop-blur text-brand"
          onClick={() => setOpen((v) => !v)}
        >
          {open ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
        </button>
      </nav>

      {open && (
        <div className="lg:hidden border-t border-slate-200/70 bg-white/95 backdrop-blur-xl shadow-lg">
          <ul className="px-6 py-4 space-y-1">
            {NAV_ITEMS.map((item) => {
              const cls = "block px-3 py-3 rounded-lg text-ink/80 hover:text-brand hover:bg-brandsky/60 font-medium";
              return (
                <li key={item.to || item.href}>
                  {item.to ? (
                    <Link to={item.to} onClick={() => setOpen(false)} className={cls}>{item.label}</Link>
                  ) : (
                    <a href={item.href} onClick={() => setOpen(false)} className={cls}>{item.label}</a>
                  )}
                </li>
              );
            })}
            <li className="pt-2">
              <Link
                to="/login"
                onClick={() => setOpen(false)}
                className="flex items-center justify-center gap-2 px-5 h-12 rounded-full bg-brand text-white font-semibold"
              >
                <LogIn className="w-4 h-4" />
                Iniciar Sesión
              </Link>
            </li>
          </ul>
        </div>
      )}
    </header>
  );
}