import { BrowserRouter as Router, Route, Routes } from "react-router-dom"

import DashboardPage from "./pages/Dashboard/DashboardPage"

function App() {

  return (
    <>
      <Router>
        <Routes>
          <Route path="/" element={<DashboardPage />} />
        </Routes>
      </Router>

    </>
  )
}

export default App
