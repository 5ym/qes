import { fail, redirect } from '@sveltejs/kit';
import { createSession, getUserByEmail, setSessionCookie, verifyPassword } from '$lib/server/auth';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ locals, url }) => {
	if (locals.user) {
		throw redirect(303, url.searchParams.get('redirect') ?? '/list');
	}
	return {};
};

export const actions: Actions = {
	default: async (event) => {
		const form = await event.request.formData();
		const email = String(form.get('email') ?? '').trim();
		const password = String(form.get('password') ?? '');
		if (!email || !password) {
			return fail(400, { email, error: 'メールアドレスとパスワードを入力してください' });
		}
		const user = getUserByEmail(email);
		if (!user || !(await verifyPassword(password, user.passwordHash))) {
			return fail(401, { email, error: 'メールアドレスまたはパスワードが違います' });
		}
		setSessionCookie(event, createSession(user.id));
		throw redirect(303, event.url.searchParams.get('redirect') ?? '/list');
	},
};
