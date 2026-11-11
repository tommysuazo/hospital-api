import React, { useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
// textarea styled inline below (native element)
import { CalendarCheck, Loader2, CheckCircle2, ChevronRight } from "lucide-react";
import { base44 } from "@/api/base44Client";

const SPECIALTIES = [
  "Pediatría",
  "Cardiología",
  "Ginecología",
  "Dermatología",
  "Gastroenterología",
  "Obstetricia",
  "Oncología",
];

export default function BookingDialog({ open, onOpenChange, initialSpecialty = "" }) {
  const [step, setStep] = useState(0);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [done, setDone] = useState(null);
  const [initialSpecialtyValue] = useState(
    () => SPECIALTIES.includes(initialSpecialty) ? initialSpecialty : ""
  );
  const [form, setForm] = useState({
    full_name: "",
    phone: "",
    email: "",
    specialty: initialSpecialtyValue,
    preferred_date: "",
    reason: "",
  });

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const reset = () => {
    setStep(0);
    setDone(null);
    setError("");
    setForm({ full_name: "", phone: "", email: "", specialty: initialSpecialtyValue, preferred_date: "", reason: "" });
  };

  const next = () => setStep((s) => s + 1);
  const back = () => setStep((s) => Math.max(0, s - 1));

  const handleOpenChange = (v) => {
    if (!v) reset();
    onOpenChange(v);
  };

  const submit = async () => {
    setError("");
    if (!form.full_name || !form.phone || !form.specialty || !form.preferred_date) {
      setError("Completa los campos obligatorios: nombre, teléfono, especialidad y fecha.");
      return;
    }
    setLoading(true);
    try {
      const created = await base44.entities.Appointment.create({
        full_name: form.full_name.trim(),
        phone: form.phone.trim(),
        email: form.email.trim() || undefined,
        specialty: form.specialty,
        preferred_date: form.preferred_date,
        reason: form.reason.trim() || undefined,
        status: "pendiente",
      });
      setDone(created);
    } catch (e) {
      setError("No pudimos registrar tu cita. Inténtalo nuevamente en unos minutos.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <Dialog open={open} onOpenChange={handleOpenChange}>
      <DialogContent className="sm:max-w-lg p-0 overflow-hidden gap-0">
        <DialogHeader className="px-6 pt-6 pb-0">
          <DialogTitle className="font-heading text-brand text-xl flex items-center gap-2">
            <CalendarCheck className="w-5 h-5 text-vitagreen" />
            Agendar Cita
          </DialogTitle>
          <DialogDescription className="text-ink/60">
            Completa el formulario y recibiremos tu solicitud de cita.
          </DialogDescription>
        </DialogHeader>

        {done ? (
          <div className="px-6 py-8 text-center">
            <span className="mx-auto grid place-items-center w-16 h-16 rounded-full bg-greensky text-vitagreen">
              <CheckCircle2 className="w-9 h-9" />
            </span>
            <h3 className="mt-5 font-heading text-2xl font-bold text-brand">¡Solicitud enviada!</h3>
            <p className="mt-2 text-ink/65 leading-relaxed">
              Gracias {done.full_name?.split(" ")[0] || ""}. Hemos registrado tu solicitud para{" "}
              <span className="font-semibold text-brand">{done.specialty}</span>
              {done.preferred_date ? ` el ${new Date(done.preferred_date).toLocaleDateString("es-ES", { day: "numeric", month: "long", year: "numeric" })}` : ""}.
              Nuestro equipo te contactará pronto para confirmar la hora.
            </p>
            <button type="button" onClick={() => handleOpenChange(false)} className="mt-6 inline-flex items-center justify-center w-full h-12 px-6 rounded-full bg-brand text-white font-semibold shadow-md shadow-brand/20 hover:bg-brandnavy active:scale-[0.98] transition-all">
              Cerrar
            </button>
          </div>
        ) : (
          <div className="px-6 pb-6">
            <div className="flex items-center gap-2 mb-6">
              {[0, 1, 2].map((i) => (
                <div key={i} className={`h-1.5 flex-1 rounded-full transition-colors ${i <= step ? "bg-vitagreen" : "bg-slate-200"}`} />
              ))}
            </div>

            {step === 0 && (
              <div className="space-y-4">
                <div>
                  <Label htmlFor="bk-name" className="text-ink">Nombre completo *</Label>
                  <Input id="bk-name" value={form.full_name} onChange={set("full_name")} placeholder="Tu nombre y apellido" className="mt-1.5 h-12" />
                </div>
                <div>
                  <Label htmlFor="bk-phone" className="text-ink">Teléfono *</Label>
                  <Input id="bk-phone" value={form.phone} onChange={set("phone")} placeholder="+1 (809) 000-0000" className="mt-1.5 h-12" />
                </div>
                <div>
                  <Label htmlFor="bk-email" className="text-ink">Correo electrónico</Label>
                  <Input id="bk-email" type="email" value={form.email} onChange={set("email")} placeholder="tucorreo@example.com" className="mt-1.5 h-12" />
                </div>
                <button type="button" onClick={next} className="inline-flex items-center justify-center gap-1 w-full h-12 px-6 rounded-full bg-vitagreen text-white font-semibold shadow-md shadow-vitagreen/20 hover:bg-[#209364] active:scale-[0.98] transition-all">
                  Continuar <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            )}

            {step === 1 && (
              <div className="space-y-4">
                <div>
                  <Label htmlFor="bk-spec" className="text-ink">Especialidad *</Label>
                  <select
                    id="bk-spec"
                    value={form.specialty}
                    onChange={set("specialty")}
                    className="mt-1.5 w-full h-12 rounded-md border border-input bg-background px-3 text-ink focus:outline-none focus:ring-2 focus:ring-vitagreen"
                  >
                    <option value="">Selecciona una especialidad</option>
                    {SPECIALTIES.map((s) => (
                      <option key={s} value={s}>{s}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <Label htmlFor="bk-date" className="text-ink">Fecha preferida *</Label>
                  <Input id="bk-date" type="date" value={form.preferred_date} onChange={set("preferred_date")} className="mt-1.5 h-12" />
                </div>
                <div className="flex gap-3">
                  <button type="button" onClick={back} className="flex-1 inline-flex items-center justify-center h-12 px-6 rounded-full border border-slate-300 bg-white text-ink font-semibold hover:bg-slate-50 active:scale-[0.98] transition-all">
                    Atrás
                  </button>
                  <button type="button" onClick={next} className="flex-1 inline-flex items-center justify-center gap-1 h-12 px-6 rounded-full bg-vitagreen text-white font-semibold shadow-md shadow-vitagreen/20 hover:bg-[#209364] active:scale-[0.98] transition-all">
                    Continuar <ChevronRight className="w-4 h-4" />
                  </button>
                </div>
              </div>
            )}

            {step === 2 && (
              <div className="space-y-4">
                <div>
                  <Label htmlFor="bk-reason" className="text-ink">Motivo de la consulta</Label>
                  <textarea id="bk-reason" value={form.reason} onChange={set("reason")} placeholder="Cuéntanos brevemente el motivo de tu cita (opcional)" className="mt-1.5 w-full min-h-[110px] rounded-md border border-input bg-background px-3 py-2 text-sm text-ink focus:outline-none focus:ring-2 focus:ring-vitagreen" />
                </div>
                <div className="rounded-xl bg-brandsky/60 p-4 text-sm text-ink/70">
                  <div className="font-semibold text-brand mb-1">Resumen</div>
                  <div>{form.full_name || "—"} · {form.specialty || "—"}</div>
                  <div>{form.phone} {form.email ? `· ${form.email}` : ""}</div>
                  <div>{form.preferred_date || "—"}</div>
                </div>
                {error && <div className="text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">{error}</div>}
                <div className="flex gap-3">
                  <button type="button" onClick={back} disabled={loading} className="flex-1 inline-flex items-center justify-center h-12 px-6 rounded-full border border-slate-300 bg-white text-ink font-semibold hover:bg-slate-50 active:scale-[0.98] transition-all disabled:opacity-60">
                    Atrás
                  </button>
                  <button type="button" onClick={submit} disabled={loading} className="flex-1 inline-flex items-center justify-center gap-1 h-12 px-6 rounded-full bg-vitagreen text-white font-semibold shadow-lg shadow-vitagreen/30 hover:bg-[#209364] active:scale-[0.98] transition-all disabled:opacity-60">
                    {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : "Enviar solicitud"}
                  </button>
                </div>
              </div>
            )}
          </div>
        )}
      </DialogContent>
    </Dialog>
  );
}