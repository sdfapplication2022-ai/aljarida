<?php

namespace App\Http\Controllers;

use App\Models\{Poll, PollOption, PollVote};
use App\Support\Visitor;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        abort_unless($poll->isOpen(), 404);

        $data = $request->validate([
            'option_id' => ['required', 'integer', Rule::exists('poll_options', 'id')->where('poll_id', $poll->id)],
            'website'   => 'prohibited', // honeypot anti-robots
        ]);

        $user = $request->user();
        $who  = $user ? ['user_id' => $user->id] : ['visitor_token' => Visitor::token($request)];

        try {
            DB::transaction(function () use ($poll, $data, $who, $request) {
                PollVote::create($who + [
                    'poll_id'        => $poll->id,
                    'poll_option_id' => (int) $data['option_id'],
                    'ip'             => $request->ip(),
                ]);
                PollOption::whereKey($data['option_id'])->increment('votes_count');
            });
            $message = 'شكرًا لمشاركتك في الاستطلاع!';
        } catch (UniqueConstraintViolationException) {
            $message = 'لقد شاركت في هذا الاستطلاع من قبل.';
        }

        // retour sur la page d'où vient le vote (accueil ou article)
        return redirect(strtok(url()->previous(), '#').'#poll')->with('poll_message', $message);
    }
}
