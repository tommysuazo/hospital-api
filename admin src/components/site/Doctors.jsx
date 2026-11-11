import React from "react";
import { BadgeCheck } from "lucide-react";

const DOCTORS = [
  { name: "Dra. Mariana Reyes", specialty: "Pediatría", initials: "MR" },
  { name: "Dr. Andrés Ferrer", specialty: "Cardiología", initials: "AF" },
  { name: "Dra. Daniela Ortiz", specialty: "Ginecología", initials: "DO" },
  { name: "Dr. Ricardo Mota", specialty: "Gastroenterología", initials: "RM" },
];

export default function Doctors() {
  return (
    <section id="doctores" className="py-20 md:py-28 bg-canvas scroll-mt-24">
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="max-w-2xl mb-12">
          <span className="inline-block text-[13px] font-semibold tracking-[0.2em] uppercase text-vitagreen mb-3">
            Doctores
          </span>
          <h2 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-brand tracking-tight">
            Profesionales en los que puedes confiar
          </h2>
          <p className="mt-4 text-ink/70 text-lg leading-relaxed">
            Nuestro equipo está formado por médicos altamente capacitados, comprometidos con tu bienestar y tu salud.
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {DOCTORS.map((doc) => (
            <div
              key={doc.name}
              className="group rounded-2xl bg-white border border-slate-100 p-6 text-center shadow-[0_14px_40px_-26px_rgba(39,68,129,0.4)] transition-all duration-300"
            >
              <div className="relative mx-auto w-24 h-24">
                <div className="w-24 h-24 rounded-full bg-gradient-to-br from-brandsky to-greensky grid place-items-center font-heading text-2xl font-bold text-brand">
                  {doc.initials}
                </div>
                <span className="absolute bottom-1 right-1 grid place-items-center w-7 h-7 rounded-full bg-vitagreen text-white ring-2 ring-white">
                  <BadgeCheck className="w-4 h-4" />
                </span>
              </div>
              <h3 className="mt-5 font-heading text-lg font-bold text-brand">{doc.name}</h3>
              <p className="mt-1 text-vitagreen font-semibold text-sm">{doc.specialty}</p>
              <p className="mt-3 text-ink/60 text-sm leading-relaxed">
                Atención cercana y basada en evidencia para cada paciente.
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}