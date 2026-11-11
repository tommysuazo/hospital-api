import React, { useMemo, useRef, useState } from "react";
import { Stethoscope, CalendarDays, Search, Filter, UsersRound, SearchX, RotateCcw } from "lucide-react";
import Navbar from "@/components/site/Navbar";
import Footer from "@/components/site/Footer";
import DoctorCard from "@/components/site/DoctorCard";
import { DOCTORS, SPECIALTIES, isAvailableOn, toISO } from "@/data/doctors";

export default function Doctors() {
  const [specialty, setSpecialty] = useState("all");
  const [date, setDate] = useState("");
  const [name, setName] = useState("");
  const gridRef = useRef(null);

  const todayISO = toISO(new Date());

  const filtered = useMemo(() => {
    return DOCTORS.filter((d) => {
      if (specialty !== "all" && !d.specialties.includes(specialty)) return false;
      if (name.trim() && !d.name.toLowerCase().includes(name.trim().toLowerCase())) return false;
      if (date && !isAvailableOn(d, date)) return false;
      return true;
    });
  }, [specialty, date, name]);

  const hasFilters = specialty !== "all" || date !== "" || name.trim() !== "";

  const clear = () => {
    setSpecialty("all");
    setDate("");
    setName("");
  };

  const submit = (e) => {
    e.preventDefault();
    if (gridRef.current) gridRef.current.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  const countText =
  filtered.length === 1 ?
  "1 doctor encontrado" :
  `${filtered.length} doctores encontrados`;

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main className="pt-28 md:pt-32">
        {/* Page header */}
        <section className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="max-w-2xl">
            


            
            <h1 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-brand tracking-tight text-center">
              Busca al especialista ideal para ti
            </h1>
            


            
          </div>
        </section>

        {/* Highlighted filter panel */}
        <section className="max-w-7xl mx-auto px-6 lg:px-8 mt-10">
          <div className="rounded-3xl bg-gradient-to-br from-brandsky to-greensky/40 border border-white shadow-[0_24px_60px_-32px_rgba(39,68,129,0.45)] p-6 md:p-8">
            <div className="flex items-center gap-2 mb-5">
              <span className="grid place-items-center w-9 h-9 rounded-xl bg-brand text-white shadow-md shadow-brand/30">
                <Filter className="w-4 h-4" />
              </span>
              <h2 className="font-heading text-lg md:text-xl font-bold text-brand">
                Buscar y filtrar doctores
              </h2>
            </div>

            <form onSubmit={submit}>
              <div className="grid sm:grid-cols-3 gap-4 md:gap-5">
                {/* Especialidad */}
                <div>
                  <label htmlFor="f-spec" className="flex items-center gap-1.5 text-sm font-semibold text-ink mb-1.5">
                    <Stethoscope className="w-4 h-4 text-vitagreen" />
                    Especialidad
                  </label>
                  <select
                    id="f-spec"
                    value={specialty}
                    onChange={(e) => setSpecialty(e.target.value)}
                    className="w-full h-12 rounded-xl border border-white bg-white/90 px-3 text-ink shadow-sm focus:outline-none focus:ring-2 focus:ring-vitagreen">
                    
                    <option value="all">Todas las especialidades</option>
                    {SPECIALTIES.map((s) =>
                    <option key={s} value={s}>{s}</option>
                    )}
                  </select>
                </div>

                {/* Fecha deseada */}
                <div>
                  <label htmlFor="f-date" className="flex items-center gap-1.5 text-sm font-semibold text-ink mb-1.5">
                    <CalendarDays className="w-4 h-4 text-vitagreen" />
                    Disponibilidad
                  </label>
                  <div className="relative">
                    <input
                      id="f-date"
                      type="date"
                      value={date}
                      min={todayISO}
                      onChange={(e) => setDate(e.target.value)}
                      placeholder="Seleccionar fecha"
                      className="w-full h-12 rounded-xl border border-white bg-white/90 px-3 text-ink shadow-sm focus:outline-none focus:ring-2 focus:ring-vitagreen placeholder:text-ink/40" />
                    
                  </div>
                  <p className="mt-1 text-[12px] text-ink/55">Selecciona una fecha para ver doctores disponibles ese día.</p>
                </div>

                {/* Buscar doctor */}
                <div>
                  <label htmlFor="f-name" className="flex items-center gap-1.5 text-sm font-semibold text-ink mb-1.5">
                    <Search className="w-4 h-4 text-vitagreen" />
                    Buscar doctor
                  </label>
                  <input
                    id="f-name"
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Nombre del doctor"
                    className="w-full h-12 rounded-xl border border-white bg-white/90 px-3 text-ink shadow-sm placeholder:text-ink/40 focus:outline-none focus:ring-2 focus:ring-vitagreen" />
                  
                </div>
              </div>

              <div className="mt-5 flex flex-col sm:flex-row gap-3">
                <button
                  type="submit"
                  className="inline-flex items-center justify-center gap-2 h-12 px-7 rounded-full bg-vitagreen text-white font-semibold shadow-lg shadow-vitagreen/30 hover:bg-greenink active:scale-[0.98] transition-all sm:px-8">
                  
                  <Search className="w-4 h-4" />
                  Buscar doctores
                </button>
                <button
                  type="button"
                  onClick={clear}
                  className="inline-flex items-center justify-center gap-2 h-12 px-7 rounded-full bg-white text-ink font-semibold border border-slate-200 shadow-sm hover:bg-slate-50 active:scale-[0.98] transition-all">
                  
                  <RotateCcw className="w-4 h-4" />
                  Limpiar filtros
                </button>
              </div>
            </form>
          </div>
        </section>

        {/* Results */}
        <section ref={gridRef} className="max-w-7xl mx-auto px-6 lg:px-8 mt-10 md:mt-12 pb-20 md:pb-28 scroll-mt-28">
          <div className="flex items-center gap-2 mb-6">
            <UsersRound className="w-5 h-5 text-brand/70" />
            <p className="text-ink/75 font-medium">
              {hasFilters && <span className="text-brand font-semibold">{countText}</span>}
              {!hasFilters && <span>{DOCTORS.length} doctores disponibles</span>}
            </p>
          </div>

          {filtered.length > 0 ?
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {filtered.map((doc) =>
            <DoctorCard key={doc.id} doctor={doc} />
            )}
            </div> :

          <div className="rounded-2xl border border-slate-100 bg-canvas py-16 px-6 text-center">
              <span className="mx-auto grid place-items-center w-16 h-16 rounded-full bg-brandsky text-brand">
                <SearchX className="w-8 h-8" />
              </span>
              <h3 className="mt-5 font-heading text-xl font-bold text-brand">
                No encontramos doctores disponibles con los filtros seleccionados.
              </h3>
              <p className="mt-2 text-ink/60 max-w-md mx-auto">
                Prueba ajustando la especialidad o la fecha para ampliar tu búsqueda.
              </p>
              <button
              type="button"
              onClick={clear}
              className="mt-6 inline-flex items-center justify-center gap-2 h-12 px-7 rounded-full bg-vitagreen text-white font-semibold shadow-lg shadow-vitagreen/30 hover:bg-greenink active:scale-[0.98] transition-all">
              
                <RotateCcw className="w-4 h-4" />
                Limpiar filtros
              </button>
            </div>
          }
        </section>
      </main>
      <Footer />
    </div>);

}