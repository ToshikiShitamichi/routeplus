import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider, useAuth } from "./contexts/AuthContext";
import LoginPage from "./pages/LoginPage";
import Dashboard from "./pages/Dashboard";
import Submissions from './pages/Submissions';
import AdminDashboard from './pages/Admin';
import RegisterPage from './pages/Register';
import RegisterPublic from './pages/RegisterPublic';
import SelectPack from './pages/SelectPack';
import Portfolio from './pages/Portfolio';
import OperatorDashboard from './pages/Operator';
import RegisterAdminPage from './pages/RegisterAdmin';

function ProtectedRoute({ children }) {
  const { user, authChecked } = useAuth();

  if (!authChecked) {
    return <div style={{ padding: "40px" }}>認証確認中...</div>;
  }

  if (!user) {
    return <Navigate to="/" replace />;
  }

  return children;
}

function AppRoutes() {
  const { user, authChecked } = useAuth();

  if (!authChecked) {
    return <div style={{ padding: "40px" }}>読み込み中...</div>;
  }

  return (
    <Routes>
      <Route
        path="/"
        element={
          user
            ? user.role === 'operator'
              ? <Navigate to="/operator" replace />
              : user.role === 'admin'
                ? <Navigate to="/admin" replace />
                : <Navigate to="/dashboard" replace />
            : <LoginPage />
        }
      />
      <Route
        path="/dashboard"
        element={
          <ProtectedRoute>
            <Dashboard />
          </ProtectedRoute>
        }
      />
      <Route path="/submissions" element={<Submissions />} />
      <Route path="/admin" element={<AdminDashboard />} />
      <Route path="/register" element={<RegisterPage />} />
      <Route path="/register/public" element={<RegisterPublic />} />
      <Route path="/select-pack" element={<SelectPack />} />
      <Route path="/portfolio/:userId" element={<Portfolio />} />
      <Route path="/operator" element={<OperatorDashboard />} />
      <Route path="/register/admin" element={<RegisterAdminPage />} />
    </Routes>
  );
}

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <AppRoutes />
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;