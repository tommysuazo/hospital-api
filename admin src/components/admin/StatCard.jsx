import React from "react";
import { cn } from "@/lib/utils";

export default function StatCard({ icon: Icon, label, value, tone = "brand", sub }) {
  const tones = {
    brand: "bg-brand text-white",
    green: "bg-vitagreen text-white",
    sky: "bg-sky-600 text-white",
    amber: "bg-amber-500 text-white",
    rose: "bg-rose-500 text-white",
    violet: "bg-violet-600 text-white",
  };
  return (
    <div className="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
      <div className="flex items-center justify-between">
        <span className={cn("grid place-items-center w-11 h-11 rounded-xl shadow-sm", tones[tone])}>
          {Icon && <Icon className="w-5 h-5" />}
        </span>
        {sub && <span className="text-[12px] font-semibold text-emerald-600">{sub}</span>}
      </div>
      <div className="mt-4">
        <div className="font-heading text-3xl font-extrabold text-ink leading-none">{value}</div>
        <div className="mt-1.5 text-[13px] text-ink/55 font-medium">{label}</div>
      </div>
    </div>
  );
}