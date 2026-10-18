<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResources;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/** Contrôleur CRUD générique piloté par App\Support\AdminResources. */
class ResourceController extends Controller
{
    protected function cfg(string $resource): array
    {
        $cfg = AdminResources::all()[$resource] ?? abort(404);
        if (($cfg['adminOnly'] ?? false) && ! auth()->user()->isAdmin()) {
            abort(403);
        }
        return $cfg + ['create' => true, 'edit' => true, 'key' => $resource];
    }

    public function index(Request $request, string $resource)
    {
        $cfg = $this->cfg($resource);
        $q = $cfg['model']::query()->with($cfg['with'] ?? []);

        if (($s = trim((string) $request->query('q'))) !== '' && ! empty($cfg['search'])) {
            $q->where(function ($w) use ($cfg, $s) {
                foreach ($cfg['search'] as $col) {
                    $w->orWhere($col, 'like', "%{$s}%");
                }
            });
        }

        return view('admin.crud.index', [
            'cfg' => $cfg,
            'items' => $q->latest('id')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(string $resource)
    {
        $cfg = $this->cfg($resource);
        abort_unless($cfg['create'], 404);

        $view = $cfg['formView'] ?? 'admin.crud.form';   // 👈
        return view($view, ['cfg' => $cfg, 'item' => new $cfg['model']]);
    }

    public function store(Request $request, string $resource)
    {
        $cfg = $this->cfg($resource);
        abort_unless($cfg['create'], 404);

        $item = new $cfg['model'];
        $this->save($request, $cfg, $item);

        return redirect()->route('admin.crud.index', $resource)->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        abort_unless($cfg['edit'], 404);

        $view = $cfg['formView'] ?? 'admin.crud.form';   // 👈
        return view($view, ['cfg' => $cfg, 'item' => $cfg['model']::findOrFail($id)]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        abort_unless($cfg['edit'], 404);

        $this->save($request, $cfg, $cfg['model']::findOrFail($id));

        return redirect()->route('admin.crud.index', $resource)->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $item = $cfg['model']::findOrFail($id);

        if ($resource === 'users' && $item->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $item->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    protected function save(Request $request, array $cfg, $item): void
    {
        $id = $item->id ?? 'NULL';
        $rules = [];
        foreach ($cfg['fields'] as $f) {
            $rules[$f[0]] = str_replace('{id}', (string) $id, $f[3]);
            if ($f[2] === 'tags') {
                $rules[$f[0].'.*'] = 'integer';
            }
        }
        $request->validate($rules);

        $data = [];
        $relations = [];
        foreach ($cfg['fields'] as $f) {
            [$name, , $type] = $f;
            switch ($type) {
                case 'file':
                    if ($request->hasFile($name)) {
                        $data[$name] = $request->file($name); // le mutator gère l'upload
                    }
                    break;
                case 'checkbox':
                    $data[$name] = $request->boolean($name);
                    break;
                case 'password':
                    if ($request->filled($name)) {
                        $data[$name] = $request->input($name);
                    }
                    break;
                case 'tags':
                    $relations[$name] = (array) $request->input($name, []);
                    break;
                case 'richtext':
                    $v = $request->input($name);
                    $v = is_string($v) ? trim(clean($v)) : null;
                    $data[$name] = ($v === '' || $v === '<p></p>' || $v === '<p><br></p>') ? null : $v;
                    break;
                default:
                    $data[$name] = $request->input($name) === '' ? null : $request->input($name);
            }
        }

        // slug automatique (Unicode) pour catégories / tags
        if (! empty($cfg['slugFrom'])) {
            $base = $data['slug'] ?? null;
            $base = $base ?: trim(preg_replace('/[^\p{L}\p{N}]+/u', '-', (string) $data[$cfg['slugFrom']]), '-');
            $slug = $base = $base ?: Str::random(6);
            $i = 1;
            while ($cfg['model']::where('slug', $slug)->where('id', '!=', $item->id ?? 0)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $data['slug'] = $slug;
        }

        // article : date de publication par défaut
        if ($cfg['key'] === 'articles' && ! empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if ($cfg['key'] === 'articles' && empty($data['is_published'])) {
            $data['is_published'] = false;
        }
        if ($cfg['key'] === 'articles' && empty($item->user_id)) {
            $data['user_id'] = auth()->id();
        }

        $item->fill($data)->save();

        foreach ($relations as $rel => $ids) {
            $item->{$rel}()->sync($ids);
        }
    }
}
