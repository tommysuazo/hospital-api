import React, { useState } from "react";
import { Baby, HeartPulse, Stethoscope, Sparkles, FlaskConical, Heart, Activity, Microscope, Eye, Bone, ArrowRight, Search } from "lucide-react";
import { Link } from "react-router-dom";

const SPECIALTIES = [
{ name: "Pediatría", icon: Baby, desc: "Cuidado integral para neonatos, niños y adolescentes, con un trato cercano y humano.", tone: "white", tall: true },
{ name: "Cardiología", icon: HeartPulse, desc: "Diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular.", tone: "navy", tall: false },
{ name: "Ginecología", icon: Stethoscope, desc: "Salud íntegra de la mujer en cada etapa de la vida.", tone: "white", tall: false },
{ name: "Oncología", icon: Activity, desc: "Acompañamiento especializado, diagnóstico oportuno y tratamiento oncológico con calidez.", tone: "green", tall: true },
{ name: "Dermatología", icon: Sparkles, desc: "Salud y cuidado de la piel, cabello y mucosas.", tone: "white", tall: false },
{ name: "Gastroenterología", icon: FlaskConical, desc: "Diagnóstico y manejo de afecciones del aparato digestivo y el hígado.", tone: "white", tall: false },
{ name: "Obstetricia", icon: Heart, desc: "Atención prenatal, parto y puerperio con un enfoque seguro y cercano para ti y tu bebé.", tone: "white", tall: true },
{ name: "Oftalmología", icon: Eye, desc: "Diagnóstico y tratamiento de afecciones visuales y enfermedades de los ojos.", tone: "white", tall: false },
{ name: "Traumatología", icon: Bone, desc: "Atención de lesiones y enfermedades del sistema musculoesquelético.", tone: "white", tall: false }];


function Card({ spec }) {
  const Icon = spec.icon;
  const isNavy = spec.tone === "navy";
  const isGreen = spec.tone === "green";

  return (
    <div
      className={`group mb-5 break-inside-avoid rounded-2xl border p-6 transition-all duration-300 ${
      isNavy ?
      "bg-brand text-white border-brand/0 shadow-[0_18px_50px_-20px_rgba(39,68,129,0.55)]" :
      isGreen ?
      "bg-greensky text-ink border-vitagreen/20 shadow-[0_18px_50px_-22px_rgba(43,174,120,0.35)]" :
      "bg-white text-ink border-slate-100 shadow-[0_14px_40px_-24px_rgba(39,68,129,0.28)] hover:shadow-[0_22px_50px_-22px_rgba(39,68,129,0.4)]"}`
      }>
      
      <div className="flex items-center gap-2.5">
        <span
          className={`inline-grid place-items-center w-12 h-12 rounded-xl transition-transform group-hover:scale-110 ${
          isNavy ?
          "bg-white/15 text-white" :
          isGreen ?
          "bg-vitagreen text-white" :
          "bg-brandsky text-brand"}`
          }>
          
          <Icon className="w-6 h-6" />
        </span>
        <h3 className={`font-heading text-xl font-bold ${isNavy ? "text-white" : "text-brand"}`}>
          {spec.name}
        </h3>
      </div>
      <p className={`mt-2 leading-relaxed text-sm ${isNavy ? "text-white/80" : "text-slate-500"}`}>
        {spec.desc}
      </p>
      <div
        className={`mt-5 inline-flex items-center gap-2 text-sm font-semibold group-hover:gap-3 transition-all ${
        isNavy ? "text-vitagreen" : isGreen ? "text-greenink" : "text-brand"}`
        }>
        
        <ArrowRight className="w-4 h-4" />
        Conocer médicos
      </div>
    </div>);

}

export default function Specialties() {
  const [query, setQuery] = useState("");
  const filtered = SPECIALTIES.filter((s) =>
  s.name.toLowerCase().includes(query.trim().toLowerCase())
  );

  return (
    <section id="especialidades" className="py-20 md:py-28 bg-white scroll-mt-24">
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="max-w-2xl mb-12">
          <span className="inline-block text-[13px] font-semibold tracking-[0.2em] uppercase text-vitagreen mb-3">
            Especialidades Médicas
          </span>
          <h2 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-brand tracking-tight">
            Un equipo experto para cada necesidad
          </h2>
          <p className="mt-4 text-slate-500 text-lg leading-relaxed">
            Contamos con especialistas en las principales áreas de la medicina para acompañarte de principio a fin.
          </p>
        </div>

        <div className="relative w-full max-w-md mb-10">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          




          
          
        </div>

        {filtered.length === 0 ?
        <div className="text-center py-16">
            <p className="text-slate-500 text-lg">
              No encontramos especialidades que coincidan con “{query}”.
            </p>
          </div> :

        <div className="columns-1 sm:columns-2 lg:columns-3 gap-5 [column-fill:balance]">
            {filtered.map((spec) =>
          <Card key={spec.name} spec={spec} />
          )}
          </div>
        }

        <div className="mt-12 flex justify-center">
          <Link
            to="/especialidades"
            className="inline-flex items-center gap-2 h-13 px-8 py-3.5 rounded-full bg-brand text-white font-semibold shadow-md shadow-brand/20 hover:bg-brandnavy active:scale-[0.98] transition-all"
            style={{ minHeight: "48px" }}>
            
            Ver todas las especialidades
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      </div>
    </section>);

}