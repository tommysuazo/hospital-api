import React from "react";
import { cn } from "@/lib/utils";
import { ChevronRight } from "lucide-react";
import { Link } from "react-router-dom";

export default function PageHeader({ title, description, breadcrumb = [], actions }) {
  return (
    <div className="mb-6">
      {breadcrumb.length > 0 && (
        <nav className="flex items-center text-[12px] text-ink/45 mb-2 flex-wrap">
          {breadcrumb.map((b, i) => (
            <span key={i} className="flex items-center">
              {i > 0 && <ChevronRight className="w-3 h-3 mx-1 text-ink/30" />}
              {b.to ? (
                <Link to={b.to} className="hover:text-brand transition-colors">{b.label}</Link>
              ) : (
                <span className="text-ink/70 font-medium">{b.label}</span>
              )}
            </span>
          ))}
        </nav>
      )}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 className="font-heading text-2xl md:text-[26px] font-extrabold text-brand tracking-tight leading-tight">
            {title}
          </h1>
          {description && <p className="mt-1 text-ink/55 text-[14px]">{description}</p>}
        </div>
        {actions && <div className="flex items-center gap-2.5 flex-wrap">{actions}</div>}
      </div>
    </div>
  );
}