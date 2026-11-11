import React from "react";
import { Link } from "react-router-dom";
import { HeartPulse, Facebook, Instagram, MessageCircle, ArrowRight } from "lucide-react";

const SERVICE_LINKS = [
  { label: "Agendar Cita", action: "book" },
  { label: "Ver Resultados", action: "login" },
  { label: "Especialidades", href: "/#especialidades" },
  { label: "Doctores", href: "/doctores" },
];

const LEGAL_LINKS = [
  { label: "Política de Privacidad", href: "#" },
  { label: "Términos y Condiciones", href: "#" },
  { label: "Aviso Legal", href: "#" },
];

export default function Footer({ onBook } = {}) {
  return (
    <footer className="bg-brandnavy text-white">
      <div className="max-w-7xl mx-auto px-6 lg:px-8 py-16 md:py-20">
        <div className="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <div className="flex items-center gap-2.5">
              <span className="grid place-items-center w-10 h-10 rounded-xl bg-vitagreen text-white">
                <HeartPulse className="w-5 h-5" />
              </span>
              <span className="leading-tight">
                <span className="block font-heading font-extrabold text-[15px] tracking-tight">Centro Médico</span>
                <span className="block font-heading font-semibold text-vitagreen text-[12px] -mt-0.5 tracking-[0.18em] uppercase">
                  Oriental
                </span>
              </span>
            </div>
            <p className="mt-5 text-white/65 leading-relaxed text-[15px] max-w-xs">
              Siempre comprometidos con tu salud. Ofrecemos atención médica integral, humana y de la más alta calidad para ti y tu familia.
            </p>
          </div>

          <div>
            <h4 className="font-heading font-bold text-white text-[15px] tracking-wide uppercase">Servicios</h4>
            <ul className="mt-5 space-y-3.5">
              {SERVICE_LINKS.map((l) =>
                l.action === "book" ? (
                  onBook ? (
                    <li key={l.label}>
                      <button onClick={onBook} className="text-white/70 hover:text-vitagreen text-[15px] transition-colors inline-flex items-center gap-1.5 group">
                        {l.label} <ArrowRight className="w-3.5 h-3.5 opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition-all" />
                      </button>
                    </li>
                  ) : (
                    <li key={l.label}>
                      <Link to="/agendar" className="text-white/70 hover:text-vitagreen text-[15px] transition-colors inline-flex items-center gap-1.5 group">
                        {l.label} <ArrowRight className="w-3.5 h-3.5 opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition-all" />
                      </Link>
                    </li>
                  )
                ) : l.action === "login" ? (
                  <li key={l.label}>
                    <a href="/login" className="text-white/70 hover:text-vitagreen text-[15px] transition-colors">{l.label}</a>
                  </li>
                ) : (
                  <li key={l.label}>
                    <a href={l.href} className="text-white/70 hover:text-vitagreen text-[15px] transition-colors">{l.label}</a>
                  </li>
                )
              )}
            </ul>
          </div>

          <div>
            <h4 className="font-heading font-bold text-white text-[15px] tracking-wide uppercase">Información Legal</h4>
            <ul className="mt-5 space-y-3.5">
              {LEGAL_LINKS.map((l) => (
                <li key={l.label}>
                  <a href={l.href} className="text-white/70 hover:text-vitagreen text-[15px] transition-colors">{l.label}</a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-heading font-bold text-white text-[15px] tracking-wide uppercase">Redes Sociales</h4>
            <div className="mt-5 flex items-center gap-3">
              {[
                { Icon: Facebook, href: "https://facebook.com", label: "Facebook" },
                { Icon: Instagram, href: "https://instagram.com", label: "Instagram" },
                { Icon: MessageCircle, href: "https://wa.me/18295550145", label: "WhatsApp" },
              ].map(({ Icon, href, label }) => (
                <a
                  key={label}
                  href={href}
                  target="_blank"
                  rel="noreferrer"
                  aria-label={label}
                  className="grid place-items-center w-11 h-11 rounded-xl bg-white/10 hover:bg-vitagreen text-white transition-colors"
                >
                  <Icon className="w-5 h-5" />
                </a>
              ))}
            </div>
            <p className="mt-6 text-white/65 text-sm leading-relaxed">
              <span className="block">+1 (809) 555-0123</span>
              <span className="block">contacto@centromedicooriental.com</span>
            </p>
          </div>
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="max-w-7xl mx-auto px-6 lg:px-8 py-6 text-center text-white/55 text-sm">
          © Centro Médico Oriental. Todos los derechos reservados.
        </div>
      </div>
    </footer>
  );
}