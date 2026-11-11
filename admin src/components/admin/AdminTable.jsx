import React from "react";
import { cn } from "@/lib/utils";

// columns: [{ key, label, headClassName?, className? }]
// renderCell(row, col) -> ReactNode
export default function AdminTable({ columns, rows, renderCell, empty = "Sin registros para mostrar", onRowClick }) {
  return (
    <div className="rounded-xl bg-white border border-slate-200 overflow-hidden shadow-sm">
      <div className="overflow-x-auto">
        <table className="w-full text-sm">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              {columns.map((c) => (
                <th key={c.key} className={cn("text-left font-semibold text-ink/60 px-4 py-3 whitespace-nowrap", c.headClassName)}>
                  {c.label}
                </th>
              ))}
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {rows.length === 0 ? (
              <tr>
                <td colSpan={columns.length} className="px-4 py-14 text-center text-ink/45">
                  {empty}
                </td>
              </tr>
            ) : (
              rows.map((row, i) => (
                <tr
                  key={row.id ?? i}
                  onClick={() => onRowClick?.(row)}
                  className={cn("transition-colors", onRowClick && "cursor-pointer", "hover:bg-slate-50/70")}
                >
                  {columns.map((c) => (
                    <td key={c.key} className={cn("px-4 py-3 align-middle text-ink/80", c.className)}>
                      {renderCell ? renderCell(row, c) : row[c.key]}
                    </td>
                  ))}
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}