import { redirect } from '@sveltejs/kit';
import { getAllEntries, statusLabel } from '$lib/server/db/repo';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
	if (!locals.user) {
		throw redirect(303, '/login?redirect=/list');
	}
	return {
		entries: getAllEntries().map((e) => ({ ...e, label: statusLabel(e.status) })),
	};
};
