import React from "react";
import { cn } from "@/lib/utils";

// tabs: [{ key, label, count? }]
export default function StatusTabs({ tabs, active, onChange }) {
  return (
    <div className="flex items-center gap-1.5 overflow-x-auto pb-1 -mb-1">
      {tabs.map((t) => {
        const isActive = t.key === active;
        return (
          <button
            key={t.key}
            onClick={() => onChange(t.key)}
            className={cn(
              "inline-flex items-center gap-2 h-9 px-3.5 rounded-full text-[13px] font-semibold whitespace-nowrap transition-colors border",
              isActive
                ? "bg-brand text-white border-brand shadow-sm"
                : "bg-white text-ink/65 border-slate-200 hover:bg-slate-50 hover:text-ink"
            )}
          >
            {t.label}
            {t.count != null && (
              <span className={cn("px-1.5 py-0.5 rounded-full text-[11px] font-bold", isActive ? "bg-white/20" : "bg-slate-100 text-ink/55")}>
                {t.count}
              </span>
            )}
          </button>
        );
      })}
    </div>
  );
}