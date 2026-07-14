<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function users(Request $request): View|RedirectResponse
    {
        $this->authorizeAdmin($request);

        $users = User::query()
            ->latest()
            ->paginate(50);

        return view('admin.users', compact('users'));
    }

    public function togglePaidMember(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $user->forceFill([
            'is_paid_member' => ! $user->is_paid_member,
            'paid_member_since' => ! $user->is_paid_member ? now() : $user->paid_member_since,
        ])->save();

        return back()->with('status', $user->name . ' の会員状態を更新しました。');
    }

    private function authorizeAdmin(Request $request): void
    {
        $adminEmail = config('membership.admin_email');

        abort_unless($request->user() && $adminEmail && $request->user()->email === $adminEmail, 403);
    }
}
