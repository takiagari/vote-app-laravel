<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Choice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        // ログイン中のユーザーの投稿のみを取得
        $articles = Article::where('user_id', auth()->id())->get();
        return Inertia::render('Articles/Index', ['articles' => $articles]);
    }

    public function create()
    {
        return inertia('Articles/Create');
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'choices.*.text.required' => '選択肢 :position の名前は必ず指定してください。',
            ];
    
            // バリデーション
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                // 画像バリデーション
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'comments_enabled' => 'required|boolean',
                'start_time' => 'required|date',
                'end_time' => 'nullable|date|after:start_time',
                // 選択肢は配列で複数持ち、最低1つの要素が必要
                'choices' => 'required|array|min:1|max:12',
                // 各選択肢のtextが必須で文字列、最大255文字
                'choices.*.text' => 'required|string|max:255',
                'choices.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'max_choices' => ['required', 'integer', 'min:1',
                    // カスタムバリデーション
                    function ($attribute, $value, $fail) use ($request) {
                        // 選択肢の数を取得し、`choices` フィールドが配列であることを確認
                        $choicesCount = count($request->input('choices', []));
                        
                        // `max_choices` の値が選択肢の数を超えていないか確認
                        if ($value > $choicesCount) {
                            // バリデーションに失敗した場合にエラーメッセージを設定
                            $fail('最大選択数は選択肢の数以下である必要があります。');
                        }
                    }
                ],
            ], $messages, [
                'choices.*.text' => '選択肢 :position'
            ]);
    
            // 認証ユーザーのIDを設定
            $validatedData['user_id'] = Auth::id(); 
    
            $article = Article::create($validatedData);
    
            // 選択肢データ作成
            foreach ($validatedData['choices'] as $choiceData) {
                \Log::info('選択肢: ' . json_encode($choiceData));
                $choice = new Choice(['title' => $choiceData['text']]);
                if (isset($choiceData['image'])) {
                    // デバッグログ
                    \Log::info('画像アリ');
                    $choice->image_path = $choiceData['image']->store('choice_images', 'public');
                }
                $article->choices()->save($choice);
            }
            
            // 画像アップロード処理
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('article_images', 'public');
                    $article->images()->create([
                        'image_path' => $imagePath,
                        'image_title' => $image->getClientOriginalName(),
                    ]);
                }
            }
    
            return redirect()->route('articles.index')->with('success', 'Article created successfully.');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }
    
    

    public function show($id)
    {
        $article = Article::with('choices', 'images')->findOrFail($id);
        return Inertia::render('Articles/Show', [
            'article' => $article,
            // 'auth' => [
            //     'user' => auth()->user(),
            // ],
        ]);
    }
}
