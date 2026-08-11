<script lang="ts">
import { invalidateAll } from '$app/navigation';
import type { PageData } from './$types';

let { data }: { data: PageData } = $props();
let loading = $state(false);

async function refresh() {
	loading = true;
	await invalidateAll();
	loading = false;
}
</script>

<svelte:head>
	<title>一覧 · QR Entry System</title>
</svelte:head>

<div class="flex flex-col gap-4">
	<div class="flex items-center justify-between">
		<h1 class="text-2xl font-bold">登録一覧</h1>
		<button type="button" class="btn btn-primary btn-sm" disabled={loading} onclick={refresh}>
			{#if loading}
				<span class="loading loading-spinner loading-sm"></span>
			{/if}
			更新
		</button>
	</div>

	{#if data.entries.length === 0}
		<div class="alert">登録はまだありません。</div>
	{:else}
		<div class="card bg-base-100 shadow-md">
			<div class="card-body overflow-x-auto p-4">
				<table class="table-zebra table">
					<thead>
						<tr>
							<th>名前</th>
							<th>連絡先</th>
							<th>住所</th>
							<th>シークレット</th>
							<th>ステータス</th>
						</tr>
					</thead>
					<tbody>
						{#each data.entries as e (e.id)}
							<tr>
								<td><a class="link" href={`/status?secret=${e.secret}`}>{e.name}</a></td>
								<td>{e.contact}</td>
								<td>{e.address}</td>
								<td class="font-mono">{e.secret}</td>
								<td>
									<span
										class="badge {e.status === 3
											? 'badge-success'
											: e.status === 0
												? 'badge-ghost'
												: 'badge-warning'}"
									>
										{e.label}
									</span>
								</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>
		</div>
	{/if}
</div>
