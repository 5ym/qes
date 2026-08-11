import { error, fail, redirect } from '@sveltejs/kit';
import { getEntryBySecret, statusLabel, toggleStatus } from '$lib/server/db/repo';
import type { Actions, PageServerLoad } from './$types';

function requireStaff(user: App.Locals['user'], redirectTo: string) {
	if (!user) throw redirect(303, `/login?redirect=${encodeURIComponent(redirectTo)}`);
}

export const load: PageServerLoad = async ({ locals, url }) => {
	const secret = url.searchParams.get('secret');
	requireStaff(locals.user, url.pathname + url.search);
	if (!secret) throw error(403, 'secret is required');
	const entry = getEntryBySecret(secret);
	if (!entry) throw error(404, 'entry not found');
	return { entry: { ...entry, label: statusLabel(entry.status) } };
};

export const actions: Actions = {
	/** pay / entry はトグル、pe は両方セット(元実装と同じ)。 */
	toggle: async ({ locals, request, url }) => {
		requireStaff(locals.user, url.pathname + url.search);
		const form = await request.formData();
		const secret = String(form.get('secret') ?? '');
		const action = String(form.get('action') ?? '');
		if (action !== 'pay' && action !== 'entry' && action !== 'pe') {
			return fail(400, { error: 'invalid action' });
		}
		const entry = getEntryBySecret(secret);
		if (!entry) return fail(404, { error: 'entry not found' });
		toggleStatus(entry, action);
		return { success: true };
	},
};
