import React from "react";
import { Link, useParams } from "react-router-dom";
import {
  ArrowLeft,
  Stethoscope,
  DoorOpen,
  Phone,
  Clock,
  Building2,
  CalendarCheck,
  ShieldCheck,
} from "lucide-react";
import Navbar from "@/components/site/Navbar";
import Footer from "@/components/site/Footer";
import { Image } from "@/components/ui/image";
import { getDoctorById, availabilityLabel } from "@/data/doctors";

export default function DoctorProfile() {
  const { id } = useParams();
  const doctor = getDoctorById(id);

  if (!doctor) {
    return (
      <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
        <Navbar />
        <main className="pt-40 pb-24 max-w-3xl mx-auto px-6 text-center">
          <h1 className="font-heading text-2xl font-bold text-brand">Doctor no encontrado</h1>
          <p className="mt-3 text-ink/65">El perfil que buscas no existe o no está disponible.</p>
          <Link
            to="/doctores"
            className="mt-6 inline-flex items-center justify-center gap-2 h-12 px-7 rounded-full bg-brand text-white font-semibold shadow-md shadow-brand/25 hover:bg-brandnavy active:scale-[0.98] transition-all"
          >
            <ArrowLeft className="w-4 h-4" />
            Volver a doctores
          </Link>
        </main>
        <Footer />
      </div>
    );
  }

  const avail = availabilityLabel(doctor);
  const later = avail.tone === "later";

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main className="pt-28 md:pt-32 pb-20 md:pb-28">
        <div className="max-w-5xl mx-auto px-6 lg:px-8">
          <Link
            to="/doctores"
            className="inline-flex items-center gap-2 text-sm font-semibold text-brand/80 hover:text-brand transition-colors mb-8"
          >
            <ArrowLeft className="w-4 h-4" />
            Volver a doctores
          </Link>

          <div className="grid md:grid-cols-[300px_1fr] gap-8 md:gap-10">
            {/* Left: photo + availability */}
            <div className="md:sticky md:top-28 self-start">
              <div className="rounded-2xl overflow-hidden border border-slate-100 shadow-[0_14px_40px_-26px_rgba(39,68,129,0.4)] bg-white">
                <Image
                  src={doctor.photo}
                  alt={doctor.name}
                  className="block w-full aspect-[4/3] object-cover"
                />
                <div className="p-5">
                  <span
                    className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold ${
                      later ? "bg-emerald-50 text-emerald-700" : "bg-greensky text-vitagreen"
                    }`}
                  >
                    <Clock className="w-3.5 h-3.5" />
                    {avail.text}
                  </span>
                  <Link
                    to={`/agendar?especialidad=${encodeURIComponent(doctor.specialties[0])}`}
                    className="mt-4 inline-flex items-center justify-center gap-2 w-full h-12 px-6 rounded-full bg-vitagreen text-white font-semibold shadow-lg shadow-vitagreen/30 hover:bg-greenink active:scale-[0.98] transition-all"
                  >
                    <CalendarCheck className="w-4 h-4" />
                    Agendar cita
                  </Link>
                </div>
              </div>
            </div>

            {/* Right: details */}
            <div>
              <h1 className="font-heading text-3xl md:text-4xl font-extrabold text-brand tracking-tight leading-tight">
                {doctor.name}
              </h1>

              <div className="mt-4 flex items-start gap-2 text-vitagreen font-semibold">
                <Stethoscope className="w-5 h-5 mt-0.5 shrink-0" />
                <span className="text-lg">{doctor.specialties.join(" · ")}</span>
              </div>

              <div className="mt-6 grid sm:grid-cols-2 gap-4">
                <div className="rounded-xl border border-slate-100 bg-canvas p-4 flex items-center gap-3">
                  <span className="grid place-items-center w-10 h-10 rounded-lg bg-brandsky text-brand shrink-0">
                    <DoorOpen className="w-5 h-5" />
                  </span>
                  <div>
                    <div className="text-[12px] text-ink/50 font-medium uppercase tracking-wide">Consultorio</div>
                    <div className="font-heading font-bold text-ink text-lg">{doctor.consultorio}</div>
                  </div>
                </div>
                <div className="rounded-xl border border-slate-100 bg-canvas p-4 flex items-center gap-3">
                  <span className="grid place-items-center w-10 h-10 rounded-lg bg-brandsky text-brand shrink-0">
                    <Phone className="w-5 h-5" />
                  </span>
                  <div>
                    <div className="text-[12px] text-ink/50 font-medium uppercase tracking-wide">Extensión</div>
                    <div className="font-heading font-bold text-ink text-lg">{doctor.extension}</div>
                  </div>
                </div>
              </div>

              <div className="mt-8">
                <h2 className="font-heading text-lg font-bold text-brand flex items-center gap-2">
                  <ShieldCheck className="w-5 h-5 text-vitagreen" />
                  Sobre el doctor
                </h2>
                <p className="mt-3 text-ink/75 leading-relaxed text-[15px]">{doctor.bio}</p>
              </div>

              <div className="mt-8 rounded-xl bg-gradient-to-br from-brandsky to-greensky/40 border border-white p-5">
                <div className="flex items-center gap-2 text-brand font-heading font-bold">
                  <Building2 className="w-5 h-5 text-vitagreen" />
                  Centro Médico Oriental
                </div>
                <p className="mt-2 text-sm text-ink/70 leading-relaxed">
                  Para confirmar horarios o reservar tu cita, comunícate con nuestra recepción o agenda
                  directamente en línea.
                </p>
              </div>
            </div>
          </div>
        </div>
      </main>
      <Footer />
    </div>
  );
}