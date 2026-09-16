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

// 札の色。3 = 支払済かつ入場済、0 = 未払い・未入場、それ以外は片方だけ済み
const tagClass = (e: { status: number }) => (e.status === 3 ? 'ok' : e.status === 0 ? '' : 'warn');
</script>

<svelte:head>
	<title>一覧 · QR Entry System</title>
</svelte:head>

<div class="stack">
	<div class="head">
		<h1>登録一覧</h1>
		<button type="button" class="mini" disabled={loading} onclick={refresh}>
			{#if loading}
				<span class="spin"></span>
			{/if}
			更新
		</button>
	</div>

	{#if data.entries.length === 0}
		<div class="note">登録はまだありません。</div>
	{:else}
		<div class="panel pad scroll-x">
			<table class="zebra">
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
							<td><a href={`/status?secret=${e.secret}`}>{e.name}</a></td>
							<td>{e.contact}</td>
							<td>{e.address}</td>
							<td class="mono">{e.secret}</td>
							<td>
								<span class="tag {tagClass(e)}">
									{e.label}
								</span>
							</td>
						</tr>
					{/each}
				</tbody>
			</table>
		</div>
	{/if}
</div>

<style>
.head {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	gap: 0.5rem;
}
/* 一行おきに地を敷く(元の table-zebra) */
.zebra tbody tr:nth-child(even) {
	background: var(--ui-base-200);
}
</style>
