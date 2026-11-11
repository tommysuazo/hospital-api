import React from "react";
import { Link } from "react-router-dom";
import { Stethoscope, DoorOpen, Phone, Clock } from "lucide-react";
import { Image } from "@/components/ui/image";
import { availabilityLabel } from "@/data/doctors";

export default function DoctorCard({ doctor }) {
  const avail = availabilityLabel(doctor);
  const later = avail.tone === "later";

  return (
    <article className="group flex flex-col rounded-2xl bg-white border border-slate-100 shadow-[0_14px_40px_-26px_rgba(39,68,129,0.4)] hover:-translate-y-1.5 hover:shadow-[0_28px_60px_-28px_rgba(39,68,129,0.5)] transition-all duration-300 overflow-hidden">
      <div className="relative">
        <Image
          src={doctor.photo}
          alt={doctor.name}
          className="block w-full aspect-[4/3] object-cover"
        />
        <span
          className={`absolute top-3 right-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold shadow-sm ring-1 ring-black/5 ${
            later ? "bg-emerald-50 text-emerald-700" : "bg-white/95 text-vitagreen"
          }`}
        >
          <Clock className="w-3.5 h-3.5" />
          {avail.text}
        </span>
      </div>

      <div className="p-5 flex flex-col flex-1">
        <h3 className="font-heading text-lg font-bold text-brand leading-tight">{doctor.name}</h3>
        <div className="mt-1.5 flex items-start gap-1.5 text-sm text-vitagreen font-semibold">
          <Stethoscope className="w-4 h-4 mt-0.5 shrink-0" />
          <span>{doctor.specialties.join(" · ")}</span>
        </div>

        <div className="mt-4 space-y-2 text-sm text-ink/75">
          <div className="flex items-center gap-2">
            <DoorOpen className="w-4 h-4 text-brand/70 shrink-0" />
            <span><span className="text-ink/45">Consultorio:</span> {doctor.consultorio}</span>
          </div>
          <div className="flex items-center gap-2">
            <Phone className="w-4 h-4 text-brand/70 shrink-0" />
            <span><span className="text-ink/45">Extensión:</span> {doctor.extension}</span>
          </div>
        </div>

        <div className="mt-5 pt-4 border-t border-slate-100 flex gap-2">
          <Link
            to={`/doctores/${doctor.id}`}
            className="flex-1 inline-flex items-center justify-center h-11 px-4 rounded-full bg-brand text-white text-[13px] font-semibold shadow-md shadow-brand/25 hover:bg-brandnavy active:scale-[0.98] transition-all"
          >
            Ver perfil
          </Link>
          <Link
            to={`/agendar?especialidad=${encodeURIComponent(doctor.specialties[0])}`}
            className="flex-1 inline-flex items-center justify-center h-11 px-4 rounded-full bg-vitagreen text-white text-[13px] font-semibold shadow-md shadow-vitagreen/25 hover:bg-[#209364] active:scale-[0.98] transition-all"
          >
            Agendar cita
          </Link>
        </div>
      </div>
    </article>
  );
}