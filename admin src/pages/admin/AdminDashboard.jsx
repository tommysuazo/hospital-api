import React from "react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from "recharts";
import { CalendarDays, CalendarClock, Users, Stethoscope, FlaskConical, AlertTriangle, ArrowUpRight, Clock } from "lucide-react";
import PageHeader from "@/components/admin/PageHeader";
import StatCard from "@/components/admin/StatCard";
import StatusBadge from "@/components/admin/StatusBadge";
import { KPI, WEEKLY_CITAS, STATUS_DISTRIBUTION, CITAS, MEDICOS_DISPONIBLES, RESULTADOS, ACTIVIDAD } from "@/data/adminMock";
import { formatDate } from "@/components/admin/ui";

const ICONS = { citasHoy: CalendarDays, pendientes: CalendarClock, atendidos: Users, medicosDisp: Stethoscope, resultados: FlaskConical, alertas: AlertTriangle };

const TONE_DOT = { green: "bg-vitagreen", sky: "bg-sky-500", brand: "bg-brand", amber: "bg-amber-500" };

export default function AdminDashboard() {
  return (
    <div>
      <PageHeader title="Dashboard" description="Resumen general de la operación del centro médico." breadcrumb={[{ label: "Inicio", to: "/admin" }, { label: "Dashboard" }]} />

      <div className="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        {KPI.map((k) => (
          <StatCard key={k.key} icon={ICONS[k.key]} label={k.label} value={k.value} tone={k.tone} sub={k.sub} />
        ))}
      </div>

      <div className="grid lg:grid-cols-3 gap-5 mt-6">
        <div className="lg:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-heading font-bold text-ink">Citas de la semana</h3>
            <span className="text-[12px] text-ink/45">Últimos 7 días</span>
          </div>
          <div style={{ width: "100%", height: 260 }}>
            <ResponsiveContainer>
              <BarChart data={WEEKLY_CITAS} margin={{ top: 4, right: 4, left: -18, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#eef2f7" />
                <XAxis dataKey="dia" tickLine={false} axisLine={false} tick={{ fontSize: 12, fill: "#94a3b8" }} />
                <YAxis tickLine={false} axisLine={false} tick={{ fontSize: 12, fill: "#94a3b8" }} />
                <Tooltip cursor={{ fill: "#f8fafc" }} contentStyle={{ borderRadius: 12, border: "1px solid #e2e8f0", fontSize: 13 }} />
                <Bar dataKey="citas" fill="#274481" radius={[6, 6, 0, 0]} barSize={26} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <h3 className="font-heading font-bold text-ink mb-4">Distribución por estado</h3>
          <div style={{ width: "100%", height: 200 }}>
            <ResponsiveContainer>
              <PieChart>
                <Pie data={STATUS_DISTRIBUTION} dataKey="value" nameKey="name" innerRadius={52} outerRadius={80} paddingAngle={2}>
                  {STATUS_DISTRIBUTION.map((d) => (
                    <Cell key={d.name} fill={d.color} />
                  ))}
                </Pie>
                <Tooltip contentStyle={{ borderRadius: 12, border: "1px solid #e2e8f0", fontSize: 13 }} />
              </PieChart>
            </ResponsiveContainer>
          </div>
          <ul className="mt-2 space-y-1.5">
            {STATUS_DISTRIBUTION.map((d) => (
              <li key={d.name} className="flex items-center justify-between text-[13px]">
                <span className="flex items-center gap-2 text-ink/65"><span className="w-2.5 h-2.5 rounded-full" style={{ background: d.color }} />{d.name}</span>
                <span className="font-semibold text-ink/80">{d.value}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>

      <div className="grid lg:grid-cols-3 gap-5 mt-5">
        {/* Próximas citas */}
        <div className="lg:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
          <div className="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 className="font-heading font-bold text-ink">Próximas citas</h3>
            <button className="text-[13px] font-semibold text-brand inline-flex items-center gap-1 hover:underline">Ver todas <ArrowUpRight className="w-3.5 h-3.5" /></button>
          </div>
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-ink/55">
              <tr>
                <th className="text-left font-semibold px-5 py-2.5">Paciente</th>
                <th className="text-left font-semibold px-5 py-2.5">Médico</th>
                <th className="text-left font-semibold px-5 py-2.5">Hora</th>
                <th className="text-left font-semibold px-5 py-2.5">Estado</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {CITAS.slice(0, 5).map((c) => (
                <tr key={c.id} className="hover:bg-slate-50/70">
                  <td className="px-5 py-3 font-medium text-ink/85">{c.paciente}</td>
                  <td className="px-5 py-3 text-ink/65">{c.medico}</td>
                  <td className="px-5 py-3 text-ink/65 inline-flex items-center gap-1"><Clock className="w-3.5 h-3.5 text-slate-400" />{c.hora}</td>
                  <td className="px-5 py-3"><StatusBadge status={c.estado} /></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Médicos disponibles */}
        <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <h3 className="font-heading font-bold text-ink mb-4">Médicos disponibles</h3>
          <ul className="space-y-3">
            {MEDICOS_DISPONIBLES.map((m) => (
              <li key={m.name} className="flex items-center gap-3">
                <span className="grid place-items-center w-9 h-9 rounded-full bg-brandsky text-brand font-semibold text-[12px]">
                  {m.name.split(" ").map((s) => s[0]).slice(0, 2).join("")}
                </span>
                <div className="min-w-0 flex-1">
                  <div className="text-[13px] font-semibold text-ink/85 truncate">{m.name}</div>
                  <div className="text-[12px] text-ink/50">{m.specialty} · Consultorio {m.office}</div>
                </div>
                <span className="text-right">
                  <div className="text-[12px] text-ink/45">Próxima</div>
                  <div className="text-[13px] font-semibold text-vitagreen">{m.nextSlot}</div>
                </span>
              </li>
            ))}
          </ul>
        </div>
      </div>

      <div className="grid lg:grid-cols-2 gap-5 mt-5">
        {/* Resultados pendientes */}
        <div className="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100">
            <h3 className="font-heading font-bold text-ink">Resultados pendientes de publicar</h3>
          </div>
          <ul className="divide-y divide-slate-100">
            {RESULTADOS.filter((r) => r.estado === "Pendiente").map((r) => (
              <li key={r.id} className="flex items-center justify-between px-5 py-3 hover:bg-slate-50/70">
                <div>
                  <div className="text-[13px] font-semibold text-ink/85">{r.paciente}</div>
                  <div className="text-[12px] text-ink/50">{r.tipo} · {r.medico}</div>
                </div>
                <StatusBadge status={r.estado} />
              </li>
            ))}
            {RESULTADOS.filter((r) => r.estado === "Pendiente").length === 0 && (
              <li className="px-5 py-8 text-center text-ink/45 text-sm">No hay resultados pendientes.</li>
            )}
          </ul>
        </div>

        {/* Actividad reciente */}
        <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
          <h3 className="font-heading font-bold text-ink mb-4">Actividad reciente</h3>
          <ul className="space-y-4">
            {ACTIVIDAD.map((a) => (
              <li key={a.id} className="flex items-start gap-3">
                <span className={`mt-1.5 w-2 h-2 rounded-full shrink-0 ${TONE_DOT[a.tone]}`} />
                <div className="min-w-0">
                  <p className="text-[13px] text-ink/80 leading-snug">{a.texto}</p>
                  <p className="text-[12px] text-ink/40 mt-0.5">{a.hora}</p>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}