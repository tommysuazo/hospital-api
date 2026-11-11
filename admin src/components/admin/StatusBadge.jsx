import React from "react";
import { cn } from "@/lib/utils";
import { STATUS_COLORS } from "./ui";

export default function StatusBadge({ status, className }) {
  const cls = STATUS_COLORS[status] || "bg-slate-100 text-slate-600 ring-1 ring-slate-200";
  return (
    <span className={cn("inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-semibold", cls, className)}>
      <span className="w-1.5 h-1.5 rounded-full bg-current opacity-70" />
      {status}
    </span>
  );
}