<script lang="ts">
import { enhance } from '$app/forms';
import { invalidateAll } from '$app/navigation';
import type { PageData } from './$types';

let { data }: { data: PageData } = $props();
let loading = $state(false);

const tagClass = $derived(data.entry.status === 3 ? 'ok' : data.entry.status === 0 ? '' : 'warn');

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

<div class="center">
	<div class="panel body box">
		<h1>入場ステータス</h1>
		<table class="info">
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
					<td><span class="tag {tagClass}">{data.entry.label}</span></td>
				</tr>
				<tr>
					<th>シークレット</th>
					<td class="mono">{data.entry.secret}</td>
				</tr>
			</tbody>
		</table>

		<div class="cluster">
			{#each [{ action: 'pay', label: '支払切替', cls: '' }, { action: 'entry', label: '入場切替', cls: 'secondary' }, { action: 'pe', label: '支払+入場', cls: 'contrast' }] as b (b.action)}
				<form method="POST" action="?/toggle" use:enhance={toggleHandler} class="act">
					<input type="hidden" name="secret" value={data.entry.secret}>
					<input type="hidden" name="action" value={b.action}>
					<button type="submit" class={b.cls} disabled={loading}>
						{b.label}
					</button>
				</form>
			{/each}
		</div>
	</div>
	<a href="/list">一覧に戻る</a>
</div>

<style>
.center {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 1.5rem;
}
.box {
	width: 100%;
	max-width: 28rem;
}
.info th {
	white-space: nowrap;
}
/* 3 つのボタンを等幅で並べる */
.act {
	flex: 1 1 0;
}
</style>
