<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Negocio;
use App\Models\Trabajador;
use App\Models\Cliente;
use App\Models\Cita;
use App\Models\Empresa\ServicioEmpresa;

class EmpresaController extends Controller
{
    /**
     * Dashboard: métricas reales + próximas citas
     */
    public function dashboard($id)
    {
        $empresa = Negocio::findOrFail($id);

        // Métricas
        $totalCitas = Cita::where('negocio_id', $empresa->id)->count();
        $serviciosActivos = ServicioEmpresa::where('negocio_id', $empresa->id)->count(); // ajusta si tienes un campo estado
        $miembrosEquipo = Trabajador::where('negocio_id', $empresa->id)->count();

        // Próximas citas (hoy en adelante)
        $proximasCitas = Cita::where('negocio_id', $empresa->id)
            ->whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(10)
            ->get();

        return view('empresa.dashboard', [
            'empresa'          => $empresa,
            'currentPage'      => 'dashboard',
            'totalCitas'       => $totalCitas,
            'serviciosActivos' => $serviciosActivos,
            'miembrosEquipo'   => $miembrosEquipo,
            'proximasCitas'    => $proximasCitas,
        ]);
    }

    public function configuracion($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion'
        ]);
    }

    public function agenda($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.agenda', [
            'empresa' => $empresa,
            'currentPage' => 'agenda'
        ]);
    }

    /**
     * Clientes: listado por negocio
     */
    public function clientes($empresaId)
    {
        $empresa  = Negocio::findOrFail($empresaId);
        $clientes = Cliente::where('negocio_id', $empresa->id)->latest()->get();

        return view('empresa.clientes.index', [
            'empresa' => $empresa,
            'clientes' => $clientes,
            'currentPage' => 'clientes',
            'currentSubPage' => null
        ]);
    }

    // 🟢 Crear cliente
    public function storeCliente(Request $request)
    {
        $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'nombre'     => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telefono'   => 'nullable|string|max:20',
        ]);

        Cliente::create([
            'negocio_id' => $request->negocio_id,
            'nombre'     => $request->nombre,
            'email'      => $request->email,
            'telefono'   => $request->telefono,
        ]);

        return redirect()->back()->with('success', 'Cliente creado correctamente.');
    }

    public function updateCliente(Request $request, $empresaId, $clienteId)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::where('negocio_id', $empresaId)->findOrFail($clienteId);
        $cliente->update([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        return redirect()->back()->with('success', 'Cliente actualizado correctamente.');
    }

    // 🔴 Eliminar cliente
    public function destroyCliente($empresaId, $clienteId)
    {
        $cliente = Cliente::where('negocio_id', $empresaId)->where('id', $clienteId)->firstOrFail();
        $cliente->delete();

        return back()->with('success', 'Cliente eliminado correctamente.');
    }

    /**
     * Trabajadores: listado por negocio
     */
    public function trabajadores($empresaId)
    {
        $empresa = Negocio::findOrFail($empresaId);
        $trabajadores = Trabajador::where('negocio_id', $empresa->id)->latest()->get();

        return view('empresa.trabajadores.index', [
            'empresa' => $empresa,
            'trabajadores' => $trabajadores,
            'currentPage' => 'trabajadores',
            'currentSubPage' => null,
        ]);
    }

    /**
     * Crear un nuevo trabajador
     */
    public function storeTrabajador(Request $request)
    {
        $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'nombre'     => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telefono'   => 'nullable|string|max:20',
        ]);

        Trabajador::create([
            'negocio_id' => $request->negocio_id,
            'nombre'     => $request->nombre,
            'email'      => $request->email,
            'telefono'   => $request->telefono,
        ]);

        return redirect()->back()->with('success', 'Trabajador creado correctamente.');
    }

    /**
     * Actualizar trabajador
     */
    public function updateTrabajador(Request $request, $empresaId, $trabajadorId)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $trabajador = Trabajador::where('negocio_id', $empresaId)->findOrFail($trabajadorId);
        $trabajador->update([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        return redirect()->back()->with('success', 'Trabajador actualizado correctamente.');
    }

    /**
     * Eliminar trabajador
     */
    public function destroyTrabajador($empresaId, $trabajadorId)
    {
        $trabajador = Trabajador::where('negocio_id', $empresaId)->where('id', $trabajadorId)->firstOrFail();
        $trabajador->delete();

        return redirect()->back()->with('success', 'Trabajador eliminado correctamente.');
    }

    // ====== CONFIGURACIÓN (subsecciones) ======

    public function configCentros($id)
    {
        $empresa = Negocio::findOrFail($id);

        // Colección virtual de centros (luego puedes cambiar por tabla real)
        $centros = collect();
        if ($empresa->neg_direccion) {
            $centros->push([
                'nombre' => $empresa->neg_nombre_comercial ?? 'Centro principal',
                'direccion' => $empresa->neg_direccion,
            ]);
        }

        return view('empresa.configuracion.centros', [
            'empresa' => $empresa,
            'centros' => $centros,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'centros'
        ]);
    }

    public function negocio($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.negocio', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'negocio',
        ]);
    }

public function indexCitas(Request $request, $id)
{
    $estados = ['pendiente','confirmada','cancelada','completada'];

    // 🔹 Carga la empresa y pásala a la vista
    $empresa = Negocio::findOrFail($id);

    $query = Cita::with([
        'trabajador:id,nombre',
        'servicio:id,nombre'
    ])->where('negocio_id', $empresa->id);

    if ($request->filled('q')) {
        $q = (string) $request->string('q');
        $query->where(function($qq) use ($q) {
            $qq->where('nombre_cliente', 'like', "%{$q}%")
               ->orWhere('notas', 'like', "%{$q}%");
        });
    }

    if ($request->filled('desde')) {
        $query->whereDate('fecha', '>=', $request->date('desde'));
    }
    if ($request->filled('hasta')) {
        $query->whereDate('fecha', '<=', $request->date('hasta'));
    }

    if ($request->filled('estado')) {
        $query->whereIn('estado', (array) $request->input('estado'));
    }

    // 🔹 Filtro por trabajador
    if ($request->filled('trabajador_id')) {
        $query->where('trabajador_id', (int) $request->input('trabajador_id'));
    }

    $perPage = (int) $request->input('per_page', 15);
    $citas = $query
        ->orderBy('fecha','desc')
        ->orderBy('hora_inicio')
        ->paginate($perPage)
        ->withQueryString();

    // Para el select de filtro
    $trabajadores = Trabajador::where('negocio_id', $empresa->id)
        ->orderBy('nombre')
        ->get(['id','nombre']);

    return view('empresa.configuracion.citas.index', [
        'id'           => $empresa->id,
        'empresa'      => $empresa,       // ← IMPORTANTE
        'citas'        => $citas,
        'estados'      => $estados,
        'trabajadores' => $trabajadores,
    ]);
}
    public function showCita(Request $request, $id, $citaId)
    {
        $empresa = Negocio::findOrFail($id);
        $cita = Cita::where('negocio_id', $empresa->id)->findOrFail($citaId);

        return view('empresa.configuracion.citas.show', [
            'empresa' => $empresa,
            'id'      => $empresa->id,
            'cita'    => $cita,
        ]);
    }

    public function cambiarEstadoCita(Request $request, $id, $citaId)
    {
        $empresa = Negocio::findOrFail($id);

        $data = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        $cita = Cita::where('negocio_id', $empresa->id)->findOrFail($citaId);
        $cita->estado = $data['estado'];
        $cita->save();

        return back()->with('success', 'Estado actualizado a: ' . ucfirst($data['estado']));
    }

    public function destroyCita(Request $request, $id, $citaId)
    {
        $empresa = Negocio::findOrFail($id);
        $cita = Cita::where('negocio_id', $empresa->id)->findOrFail($citaId);
        $cita->delete();

        return redirect()
            ->route('empresa.configuracion.citas', $empresa->id)
            ->with('success', 'Cita eliminada correctamente.');
    }

    public function configVentas($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.ventas', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'ventas'
        ]);
    }

    public function configFacturacion($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.facturacion', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'facturacion'
        ]);
    }

    public function configEquipo($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.equipo', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'equipo'
        ]);
    }

    public function configFormularios($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.formularios', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'formularios'
        ]);
    }

    public function configPagos($id)
    {
        $empresa = Negocio::findOrFail($id);

        return view('empresa.configuracion.pagos', [
            'empresa' => $empresa,
            'currentPage' => 'configuracion',
            'currentSubPage' => 'pagos'
        ]);
    }
}
