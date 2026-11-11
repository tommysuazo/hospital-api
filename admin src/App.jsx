import { Toaster } from "@/components/ui/toaster"
import { QueryClientProvider } from '@tanstack/react-query'
import { queryClientInstance } from '@/lib/query-client'
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import PageNotFound from './lib/PageNotFound';
import { AuthProvider, useAuth } from '@/lib/AuthContext';
import UserNotRegisteredError from '@/components/UserNotRegisteredError';
import ScrollToTop from './components/ScrollToTop';
import Home from './pages/Home';
import Doctors from '@/pages/Doctors';
import DoctorProfile from '@/pages/DoctorProfile';
import Booking from '@/pages/Booking';
import Specialties from '@/pages/Specialties';
import About from '@/pages/About';
import Contact from '@/pages/Contact';
import Login from '@/pages/Login';
import Register from '@/pages/Register';
import ForgotPassword from '@/pages/ForgotPassword';
import ResetPassword from '@/pages/ResetPassword';
import ProtectedRoute from '@/components/ProtectedRoute';
import AdminLayout from '@/components/admin/AdminLayout';
import AdminDashboard from '@/pages/admin/AdminDashboard';
import AdminCitas from '@/pages/admin/AdminCitas';
import AdminPacientes from '@/pages/admin/AdminPacientes';
import AdminMedicos from '@/pages/admin/AdminMedicos';
import AdminEspecialidades from '@/pages/admin/AdminEspecialidades';
import AdminServicios from '@/pages/admin/AdminServicios';
import AdminHorarios from '@/pages/admin/AdminHorarios';
import AdminInstalaciones from '@/pages/admin/AdminInstalaciones';
import AdminSeguros from '@/pages/admin/AdminSeguros';
import AdminResultados from '@/pages/admin/AdminResultados';
import AdminDocumentos from '@/pages/admin/AdminDocumentos';
import AdminPersonal from '@/pages/admin/AdminPersonal';
import AdminRoles from '@/pages/admin/AdminRoles';
import AdminAuditoria from '@/pages/admin/AdminAuditoria';
import AdminConfiguracion from '@/pages/admin/AdminConfiguracion';

const AuthenticatedApp = () => {
  const { isLoadingAuth, isLoadingPublicSettings, authError, navigateToLogin } = useAuth();

  // Show loading spinner while checking app public settings or auth
  if (isLoadingPublicSettings || isLoadingAuth) {
    return (
      <div className="fixed inset-0 flex items-center justify-center">
        <div className="w-8 h-8 border-4 border-slate-200 border-t-slate-800 rounded-full animate-spin"></div>
      </div>
    );
  }

  // Handle authentication errors
  if (authError) {
    if (authError.type === 'user_not_registered') {
      return <UserNotRegisteredError />;
    } else if (authError.type === 'auth_required') {
      // Redirect to login automatically
      navigateToLogin();
      return null;
    }
  }

  // Render the main app
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/doctores" element={<Doctors />} />
      <Route path="/doctores/:id" element={<DoctorProfile />} />
      <Route path="/agendar" element={<Booking />} />
      <Route path="/especialidades" element={<Specialties />} />
      <Route path="/nosotros" element={<About />} />
      <Route path="/contacto" element={<Contact />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
      <Route path="/forgot-password" element={<ForgotPassword />} />
      <Route path="/reset-password" element={<ResetPassword />} />

      {/* Portal administrativo (protegido) */}
      <Route element={<ProtectedRoute unauthenticatedElement={<Navigate to="/login" replace />} />}>
        <Route element={<AdminLayout />}>
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/citas" element={<AdminCitas />} />
          <Route path="/admin/pacientes" element={<AdminPacientes />} />
          <Route path="/admin/medicos" element={<AdminMedicos />} />
          <Route path="/admin/especialidades" element={<AdminEspecialidades />} />
          <Route path="/admin/servicios" element={<AdminServicios />} />
          <Route path="/admin/horarios" element={<AdminHorarios />} />
          <Route path="/admin/instalaciones" element={<AdminInstalaciones />} />
          <Route path="/admin/seguros" element={<AdminSeguros />} />
          <Route path="/admin/resultados" element={<AdminResultados />} />
          <Route path="/admin/documentos" element={<AdminDocumentos />} />
          <Route path="/admin/personal" element={<AdminPersonal />} />
          <Route path="/admin/roles" element={<AdminRoles />} />
          <Route path="/admin/auditoria" element={<AdminAuditoria />} />
          <Route path="/admin/configuracion" element={<AdminConfiguracion />} />
        </Route>
      </Route>

      <Route path="*" element={<PageNotFound />} />
    </Routes>
  );
};


function App() {

  return (
    <AuthProvider>
      <QueryClientProvider client={queryClientInstance}>
        <Router>
          <ScrollToTop />
          <AuthenticatedApp />
        </Router>
        <Toaster />
      </QueryClientProvider>
    </AuthProvider>
  )
}

export default App