<script lang="ts">
import { enhance } from '$app/forms';
import { invalidateAll } from '$app/navigation';
import type { PageData } from './$types';

let { data }: { data: PageData } = $props();
let loading = $state(false);

const badgeClass = $derived(
	data.entry.status === 3
		? 'badge-success'
		: data.entry.status === 0
			? 'badge-ghost'
			: 'badge-warning',
);

const toggleHandler = () => {
	loading = true;
	return async () => {
		await invalidateAll();
		loading = false;
	};
};
</script>

<svelte:head>
	<title>ステータス · QR Entry System</title>
</svelte:head>

<div class="flex flex-col items-center gap-6">
	<div class="card bg-base-100 w-full max-w-md shadow-lg">
		<div class="card-body gap-4">
			<h1 class="card-title">入場ステータス</h1>
			<table class="table">
				<tbody>
					<tr>
						<th>名前</th>
						<td>{data.entry.name}</td>
					</tr>
					<tr>
						<th>連絡先</th>
						<td>{data.entry.contact}</td>
					</tr>
					<tr>
						<th>住所</th>
						<td>{data.entry.address}</td>
					</tr>
					<tr>
						<th>ステータス</th>
						<td><span class="badge {badgeClass}">{data.entry.label}</span></td>
					</tr>
					<tr>
						<th>シークレット</th>
						<td class="font-mono">{data.entry.secret}</td>
					</tr>
				</tbody>
			</table>

			<div class="flex flex-wrap gap-2">
				{#each [{ action: 'pay', label: '支払切替', cls: 'btn-primary' }, { action: 'entry', label: '入場切替', cls: 'btn-secondary' }, { action: 'pe', label: '支払+入場', cls: 'btn-accent' }] as b (b.action)}
					<form method="POST" action="?/toggle" use:enhance={toggleHandler} class="flex-1">
						<input type="hidden" name="secret" value={data.entry.secret}>
						<input type="hidden" name="action" value={b.action}>
						<button type="submit" class="btn {b.cls} btn-block" disabled={loading}>
							{b.label}
						</button>
					</form>
				{/each}
			</div>
		</div>
	</div>
	<a href="/list" class="link">一覧に戻る</a>
</div>
