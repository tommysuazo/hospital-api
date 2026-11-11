import React from "react";
import {
  Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription,
} from "@/components/ui/dialog";
import { btn } from "./ui";

export function Field({ label, children, required, hint }) {
  return (
    <div>
      <label className="block text-[13px] font-semibold text-ink mb-1.5">
        {label}{required && <span className="text-rose-500"> *</span>}
      </label>
      {children}
      {hint && <p className="mt-1 text-[12px] text-ink/45">{hint}</p>}
    </div>
  );
}

const inputCls = "w-full h-11 rounded-lg border border-slate-200 bg-white px-3 text-sm text-ink focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand/40 placeholder:text-slate-400";

export function TextInput(props) {
  return <input {...props} className={`${inputCls} ${props.className || ""}`} />;
}

export function SelectInput({ children, ...props }) {
  return (
    <select {...props} className={`${inputCls} ${props.className || ""} pr-8`}>
      {children}
    </select>
  );
}

export default function FormDialog({ open, onOpenChange, title, description, children, onSave, saveLabel = "Guardar", size = "lg" }) {
  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className={size === "lg" ? "sm:max-w-lg" : "sm:max-w-2xl"}>
        <DialogHeader>
          <DialogTitle className="font-heading text-brand text-lg">{title}</DialogTitle>
          {description && <DialogDescription>{description}</DialogDescription>}
        </DialogHeader>
        <div className="grid gap-4 py-1 max-h-[60vh] overflow-y-auto pr-1">{children}</div>
        <div className="flex justify-end gap-3 pt-2 border-t border-slate-100 mt-1">
          <button type="button" className={btn("outline")} onClick={() => onOpenChange(false)}>Cancelar</button>
          <button type="button" className={btn("success")} onClick={() => { onSave?.(); onOpenChange(false); }}>{saveLabel}</button>
        </div>
      </DialogContent>
    </Dialog>
  );
}