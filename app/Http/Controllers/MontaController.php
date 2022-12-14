<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Http\Requests\MontaFormRequest;
use App\Monta;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MontaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                $monta = Monta::whereBetween('monta_fecha', array($request->from_date, $request->to_date))->get();
            } else {
                $monta = Monta::get();
            }
            return datatables()
                ->of($monta)
                ->addColumn('exito', function ($exito) {
                    if ($exito->monta_exitosa == null) {
                        return 'En espera';
                    } else {
                        return $exito->monta_exitosa;
                    }
                })
                ->addColumn('fin', function ($fin) {
                    {
                        if ($fin->monta_exitosa == null) {
                            return '<a href="' . route('embarazo.create', $fin->monta_id) . '" >
                    <button class="button btn btn-primary " onclick="return confirm(\'¿Seguro desea finalizar la monta numero '.$fin->monta_id.'? esta opción es irreversible\')">Exito</button> </a>
                    <a href="' . route('monta.fracaso', $fin->monta_id) . '">
                    <button class="button btn btn-primary " onclick="return confirm(\'¿Seguro desea finalizar la monta numero '.$fin->monta_id.'? esta opción es irreversible\')">Fracaso</button> </a>
                    ';
                        } else {
                            return '<a>
                    <button class="button btn btn-primary" disabled>Exito</button>
                </a>
                <a>
                    <button class="button btn btn-primary" disabled>Fracaso</button>
                </a>';}
                    }
                })
                ->addColumn('pdf', function ($pdf) {
                    if ($pdf->monta_exitosa == null) {
                        return '<a href="' . route('monta.individual', $pdf->monta_id) . '">
                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                    title="Informe Individual del animal"><i class="mdi mdi-file-pdf"></i>
                </button></a>
                <a href="' . route('monta.edit', $pdf->monta_id) . '">
                <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top"
                        title="editar"><i class="ti-pencil"></i>
                    </button></a>
                ';
                    } else {
                        return '<a href="' . route('monta.individual', $pdf->monta_id) . '">
                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                    title="Informe Individual del animal"><i class="mdi mdi-file-pdf"></i>
                </button></a>
                <a>
                <button class="btn btn-info btn-sm" disabled data-toggle="tooltip" data-placement="top"
                        title="editar"><i class="ti-pencil"></i>
                    </button></a>
                ';
                    }
                }
                )
                ->rawColumns(['exito', 'fin', 'pdf'])
                ->make(true);
        }

        return view('monta.index');

    }

    public function create()
    {
        $animales = Animal::where('animal_categoria', '>', 1)
            ->where('animal_estado', '=', 1)
            ->where('animal_abierto', '=', false)
            ->get();

        return view("monta.create", ["animales" => $animales]);
    }

    public function edit($id)
    {
        $monta = Monta::findOrFail($id);
        $animales = Animal::where('animal_categoria', '>', 1)
            ->where('animal_estado', '=', 1)
            ->where('animal_abierto', '=', false)
            ->orWhere('animal_id', '=', $monta->monta_madre)
            ->orWhere('animal_id', '=', $monta->monta_padre)
            ->get();
        return view("monta.edit", ["monta" => $monta, "animales" => $animales]);
    }

    public function MontaFracaso($id)
    {
        $monta = Monta::findOrFail($id);
        $monta->monta_exitosa = "No";
        $monta->update();
        $madre = Animal::findOrFail($monta->monta_madre);
        $madre->animal_estado = 1;
        $madre->update();
        return redirect('monta');
    }

    public function store(MontaFormRequest $request)
    {
        $monta = new Monta;
        $monta->monta_madre = $request->get('código_madre');
        $monta->monta_padre = $request->get('código_padre');
        $monta->monta_fecha = $request->get('fecha');
        $monta->save();
        if ($request->get('código_padre') != "inseminación") {
            $padre = Animal::findOrFail($request->get('código_padre'));
            if ($padre->animal_categoria == 2) {
                $padre->animal_categoria = 4;
                $padre->update();
            }
        }

        $madre = Animal::findOrFail($request->get('código_madre'));
        $madre->animal_estado = 5;
        $madre->update();
        $monta2 = Monta::get()->last();
        $fecha = Carbon::parse($request->get('fecha'));
        $fecha->addDays(21);
        DB::insert('insert into eventos(title, descripcion, "start", "end",id_user,monta_id) values (?,?,?,?,?,?)', ["verificación de inseminación", 'verificación de inseminación de la monta numero: ' . $monta2->monta_id, $fecha, $fecha, Auth::user()->id, $monta2->monta_id]);
        return redirect('monta');
    }

    public function update(MontaFormRequest $request, $id)
    {
        $monta = Monta::findOrFail($id);
        if ($monta->monta_madre == $request->get('código_madre')) {
            $monta->monta_madre = $request->get('código_madre');
        } else {
            $madreA = Animal::findOrFail($monta->monta_madre);
            $madreA->animal_estado = 1;
            $madreA->update();
        }

        if ($request->get('código_padre') != "inseminación") {
            $padre = Animal::findOrFail($request->get('código_padre'));
            if ($padre->animal_categoria == 2) {
                $padre->animal_categoria = 4;
                $padre->update();
            }
        }
        $monta->monta_padre = $request->get('código_padre');
        $monta->monta_fecha = $request->get('fecha');
        $monta->monta_madre = $request->get('código_madre');
        $monta->update();
        $fecha = Carbon::parse($request->get('fecha'));
        $fecha->addDays(10);
        DB::update('update eventos set title =  ? , descripcion= ? , "start" = ? , "end" = ? ,id_user = ? where monta_id = ?', ["verificación de inseminación", 'verificación de inseminación de la monta numero: ' . $monta->monta_id, $fecha, $fecha, Auth::user()->id, $monta->monta_id]);
        $madre = Animal::findOrFail($request->get('código_madre'));
        $madre->animal_estado = 5;
        $madre->update();
        return redirect('monta');

    }

    public function montaevento()
    {
        $monta = Monta::get();
        return $monta;
    }
}
