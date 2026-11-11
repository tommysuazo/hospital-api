import React from "react";
import { ShieldCheck, Award, Users, Clock } from "lucide-react";

const STATS = [
  { icon: Award, value: "+25", label: "Años de experiencia" },
  { icon: Users, value: "+40", label: "Especialistas" },
  { icon: ShieldCheck, value: "+120k", label: "Pacientes atendidos" },
  { icon: Clock, value: "24/7", label: "Urgencias disponibles" },
];

export default function About() {
  return (
    <section id="nosotros" className="py-20 md:py-28 bg-brand scroll-mt-24">
      <div className="max-w-7xl mx-auto px-6 lg:px-8 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div>
          <span className="inline-block text-[13px] font-semibold tracking-[0.2em] uppercase text-vitagreen mb-3">
            Nosotros
          </span>
          <h2 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-white tracking-tight">
            Una institución dedicada a tu bienestar
          </h2>
          <p className="mt-5 text-brandsky text-lg leading-relaxed">
            En Centro Médico Oriental combinamos tecnología de vanguardia con un trato humano y cercano. Cada paciente es
            único, y por eso diseñamos planes de atención personalizados que ponen tu salud y tu tranquilidad en el centro.
          </p>
          <p className="mt-4 text-brandsky text-lg leading-relaxed">
            Nuestra misión es ofrecer servicios médicos de la más alta calidad, con profesionales comprometidos e
            instalaciones modernas pensadas para tu seguridad y comodidad.
          </p>

          <div className="mt-8 flex flex-wrap gap-3">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-sm font-semibold">
              <ShieldCheck className="w-4 h-4 text-vitagreen" /> Calidad certificada
            </span>
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-sm font-semibold">
              <Award className="w-4 h-4 text-vitagreen" /> Equipo multidisciplinario
            </span>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-5">
          {STATS.map((s) => {
            const Icon = s.icon;
            return (
              <div
                key={s.label}
                className="rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-7 hover:bg-white/10 transition-colors"
              >
                <span className="grid place-items-center w-11 h-11 rounded-xl bg-vitagreen/15 text-vitagreen">
                  <Icon className="w-5 h-5" />
                </span>
                <div className="mt-5 font-heading text-3xl md:text-4xl font-extrabold text-white">{s.value}</div>
                <div className="mt-1 text-brandsky text-sm font-medium">{s.label}</div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}