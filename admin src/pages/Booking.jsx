import React, { useEffect, useState } from "react";
import { CalendarCheck, CalendarPlus } from "lucide-react";
import Navbar from "@/components/site/Navbar";
import Footer from "@/components/site/Footer";
import BookingDialog from "@/components/site/BookingDialog";

export default function Booking() {
  const [open, setOpen] = useState(true);
  const especialidad =
    new URLSearchParams(window.location.search).get("especialidad") || "";

  useEffect(() => {
    setOpen(true);
  }, []);

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main className="pt-28 md:pt-32 pb-20 md:pb-28">
        <div className="max-w-2xl mx-auto px-6 lg:px-8 text-center">
          <span className="mx-auto grid place-items-center w-14 h-14 rounded-2xl bg-vitagreen text-white shadow-lg shadow-vitagreen/30">
            <CalendarCheck className="w-7 h-7" />
          </span>
          <h1 className="mt-6 font-heading text-3xl md:text-4xl font-extrabold text-brand tracking-tight">
            Agendar Cita
          </h1>
          <p className="mt-4 text-ink/70 text-lg leading-relaxed">
            Completa el formulario y recibiremos tu solicitud de cita. Nuestro equipo te contactará para
            confirmar el horario.
          </p>
          <button
            type="button"
            onClick={() => setOpen(true)}
            className="mt-8 inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-vitagreen text-white font-semibold shadow-lg shadow-vitagreen/30 hover:bg-greenink active:scale-[0.98] transition-all"
          >
            <CalendarPlus className="w-4 h-4" />
            Abrir formulario de cita
          </button>
        </div>
      </main>
      <Footer />
      <BookingDialog open={open} onOpenChange={setOpen} initialSpecialty={especialidad} />
    </div>
  );
}