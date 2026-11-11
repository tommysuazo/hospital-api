import React from "react";
import { Link } from "react-router-dom";
import { CalendarCheck, FileText, ArrowRight } from "lucide-react";

export default function Services({ onBook }) {
  return (
    <section id="servicios" className="relative py-20 md:py-28 bg-canvas scroll-mt-24">
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="max-w-2xl mb-12 md:mb-16">
          <span className="inline-block text-[13px] font-semibold tracking-[0.2em] uppercase text-vitagreen mb-3">
            Servicios
          </span>
          <h2 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-brand tracking-tight">
           Todo lo que tu salud necesita, en un solo lugar
          </h2>
          <p className="mt-4 text-ink/70 text-lg leading-relaxed">
            Acciones rápidas y claras para cuidarte sin complicaciones. Reserva tu cita o consulta tus resultados en línea.
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-6 lg:gap-8">
          <button
            onClick={onBook}
            className="group text-left relative overflow-hidden rounded-3xl bg-white border border-slate-100 p-8 md:p-10 shadow-[0_10px_40px_-20px_rgba(39,68,129,0.3)] hover:shadow-[0_20px_60px_-25px_rgba(43,174,120,0.45)] hover:-translate-y-1 transition-all duration-300"
          >
            <div className="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-greensky/70 blur-2xl opacity-70 group-hover:opacity-100 transition-opacity" />
            <div className="relative">
              <span className="grid place-items-center w-16 h-16 rounded-2xl bg-vitagreen text-white shadow-lg shadow-vitagreen/30 group-hover:scale-105 transition-transform">
                <CalendarCheck className="w-8 h-8" />
              </span>
              <h3 className="mt-7 font-heading text-2xl font-bold text-brand">Agendar Cita</h3>
              <p className="mt-2 text-ink/70 text-[17px] leading-relaxed">
                Reserva tu consulta con el especialista que necesitas en pocos pasos. Elige la especialidad, fecha y hora disponibles.
              </p>
              <span className="mt-6 inline-flex items-center gap-2 text-vitagreen font-semibold group-hover:gap-3 transition-all">
                Reservar ahora <ArrowRight className="w-4 h-4" />
              </span>
            </div>
          </button>

          <Link
            to="/login"
            className="group relative overflow-hidden rounded-3xl bg-white border border-slate-100 p-8 md:p-10 shadow-[0_10px_40px_-20px_rgba(39,68,129,0.3)] hover:shadow-[0_20px_60px_-25px_rgba(39,68,129,0.45)] hover:-translate-y-1 transition-all duration-300"
          >
            <div className="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-brandsky blur-2xl opacity-70 group-hover:opacity-100 transition-opacity" />
            <div className="relative">
              <span className="grid place-items-center w-16 h-16 rounded-2xl bg-brand text-white shadow-lg shadow-brand/30 group-hover:scale-105 transition-transform">
                <FileText className="w-8 h-8" />
              </span>
              <h3 className="mt-7 font-heading text-2xl font-bold text-brand">Ver Resultados</h3>
              <p className="mt-2 text-ink/70 text-[17px] leading-relaxed">
                Consulta tus exámenes de laboratorio y estudios médicos de forma segura desde el portal de pacientes.
              </p>
              <span className="mt-6 inline-flex items-center gap-2 text-brand font-semibold group-hover:gap-3 transition-all">
                Acceder al portal <ArrowRight className="w-4 h-4" />
              </span>
            </div>
          </Link>
        </div>
      </div>
    </section>
  );
}