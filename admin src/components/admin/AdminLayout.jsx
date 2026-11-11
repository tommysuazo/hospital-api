import React, { useState } from "react";
import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar";
import Topbar from "./Topbar";

export default function AdminLayout() {
  const [open, setOpen] = useState(false);
  return (
    <div className="min-h-screen bg-slate-50 text-ink">
      <Sidebar open={open} onClose={() => setOpen(false)} />
      <div className="lg:pl-64 flex flex-col min-h-screen">
        <Topbar onMenu={() => setOpen(true)} />
        <main className="flex-1 p-4 md:p-6 lg:p-8">
          <Outlet />
        </main>
      </div>
    </div>
  );
}