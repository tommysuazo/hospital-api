import React from "react";
import { Phone, Mail, MapPin, MessageCircle, CalendarCheck, Stethoscope } from "lucide-react";

const HERO_IMG =
  "https://media.base44.com/images/public/6a8d4f320facb49af59a01eb/9a3edf518_de673654-0603-43e2-aeef-ffb89f212cab.jpg";

const CONTACT = [
  { Icon: Phone, waIcon: MessageCircle, label: "Teléfono", value: "+1 (809) 555-0123", href: "tel:+18095550123" },
  { Icon: Mail, label: "Correo electrónico", value: "contacto@centromedicooriental.do", href: "mailto:contacto@centromedicooriental.do" },
  { Icon: MapPin, label: "Ubicación", value: "Av. Integración #45, Santo Domingo", href: "#contacto" },
];

export default function Hero({ onBook }) {
  return (
    <section
      id="inicio"
      className="relative min-h-screen flex flex-col justify-center overflow-hidden scroll-mt-24"
    >
      {/* Background */}
      <div className="absolute inset-0">
        <img src={HERO_IMG} alt="Instalaciones del Centro Médico Oriental" className="w-full h-full object-cover object-bottom" />
        <div className="absolute inset-0 bg-gradient-to-r from-brandnavy/95 via-brand/80 to-brand/40" />
        <div className="absolute inset-0 bg-brand/45 mix-blend-multiply" />
      </div>

      {/* Content */}
      <div className="relative max-w-7xl mx-auto px-6 lg:px-8 w-full pt-32 pb-16 lg:py-32">
        <div className="max-w-3xl mx-auto text-center">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-white/90 text-sm font-medium">
            <Stethoscope className="w-4 h-4 text-vitagreen" />
            Atención médica de excelencia
          </div>

          <h1 className="mt-6 font-heading text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.05]">
            Centro Médico
            <span className="block text-vitagreen">Oriental</span>
          </h1>

          <p className="mt-5 text-xl md:text-2xl text-white/85 font-light tracking-tight">
            “Siempre Comprometidos con tu salud”
          </p>

          <p className="mt-4 text-white/70 text-lg leading-relaxed max-w-xl mx-auto">
            Cuidado integral y humano con un equipo multidisciplinario dedicado a tu bienestar en cada etapa de la vida.
          </p>

          {/* Contact glass bar */}
          <div className="mt-12 grid sm:grid-cols-3 gap-3 max-w-3xl mx-auto">
            {CONTACT.map(({ Icon, waIcon, label, value, href }) => {
              const Wa = waIcon;
              return (
                <a
                  key={label}
                  href={href}
                  className="group rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 p-4 hover:bg-white/15 transition-colors"
                >
                  <div className="flex items-center gap-3">
                    <span className="grid place-items-center w-10 h-10 rounded-xl bg-vitagreen/90 text-white shrink-0">
                      <Icon className="w-5 h-5" />
                    </span>
                    <div className="min-w-0">
                      <div className="text-[12px] uppercase tracking-wider text-white/55 font-semibold flex items-center gap-1">
                        {label}
                        {Wa && <Wa className="w-3.5 h-3.5 text-vitagreen" />}
                      </div>
                      <div className="text-white font-medium text-sm truncate">{value}</div>
                    </div>
                  </div>
                </a>
              );
            })}
          </div>

          <div className="mt-6 flex justify-center">
            <button
              onClick={onBook}
              className="inline-flex items-center gap-2 h-13 px-[68px] py-3.5 rounded-full bg-vitagreen text-white font-semibold shadow-md shadow-vitagreen/20 hover:bg-[#209364] active:scale-[0.98] transition-all"
              style={{ minHeight: "48px" }}
            >
              <CalendarCheck className="w-5 h-5" />
              Agendar Cita
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}