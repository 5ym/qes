<script lang="ts">
import type { SubmitFunction } from '@sveltejs/kit';
import { enhance } from '$app/forms';
import { page } from '$app/state';
import type { ActionData } from './$types';

let { form }: { form: ActionData } = $props();
let loading = $state(false);
let qrDataUrl = $state('');

// 発行済みシークレットの QR(/status?secret=... への URL)を描画する
$effect(() => {
	const secret = form?.secret;
	if (!secret) return;
	(async () => {
		const QRCode = (await import('qrcode')).default;
		const url = `${page.url.origin}/status?secret=${secret}`;
		qrDataUrl = await QRCode.toDataURL(url, { width: 280, margin: 2 });
	})();
});

const submitHandler: SubmitFunction = () => {
	loading = true;
	return async ({ update }) => {
		await update({ reset: false });
		loading = false;
	};
};
</script>

<svelte:head>
	<title>QR Entry System</title>
</svelte:head>

{#if form?.secret}
	<div class="center">
		<div class="panel body ticket">
			<h1>あなたのシークレット</h1>
			<p class="secret mono">{form.secret}</p>
			{#if qrDataUrl}
				<img src={qrDataUrl} alt="入場用QRコード" width="280" height="280">
			{:else}
				<div class="qr-wait"></div>
			{/if}
			<p class="small muted">
				このQRコードを受付でご提示ください。シークレットは再発行に必要なので控えてください。
			</p>
		</div>
	</div>
{:else}
	<div class="center">
		<section class="col">
			<h1>入場登録</h1>
			<div class="panel body">
				{#if form?.error}
					<div class="note err">{form.error}</div>
				{/if}
				<form method="POST" action="?/register" use:enhance={submitHandler} class="stack">
					<label class="field">
						<span class="lab">名前</span>
						<input name="name" type="text" required maxlength="255">
					</label>
					<label class="field">
						<span class="lab">連絡先</span>
						<input name="contact" type="text" required maxlength="255">
					</label>
					<label class="field">
						<span class="lab">住所</span>
						<input name="address" type="text" required maxlength="255">
					</label>
					<button type="submit" disabled={loading}>
						{#if loading}
							<span class="spin"></span>
						{/if}
						登録
					</button>
				</form>
			</div>
		</section>

		<section class="col">
			<h2>QRチケットの再表示</h2>
			<div class="panel body">
				{#if form?.reissueError}
					<div class="note warn">{form.reissueError}</div>
				{/if}
				<form method="POST" action="?/reissue" use:enhance={submitHandler} class="stack">
					<label class="field">
						<span class="lab">シークレット</span>
						<input
							name="secret"
							type="text"
							required
							inputmode="numeric"
							class="mono"
							placeholder="9桁の数字"
						>
					</label>
					<button type="submit" class="secondary" disabled={loading}>
						{#if loading}
							<span class="spin"></span>
						{/if}
						再表示
					</button>
				</form>
			</div>
		</section>
	</div>
{/if}

<style>
.center {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 2rem;
}
/* 画面が広くても読みやすい幅で止める */
.col,
.ticket {
	width: 100%;
	max-width: 28rem;
}
.col {
	display: flex;
	flex-direction: column;
	gap: 1rem;
}
.ticket {
	align-items: center;
	text-align: center;
}
.secret {
	font-size: 1.875rem;
	font-weight: 700;
	letter-spacing: 0.05em;
}
/* QR を描き終えるまでの場所取り。出来上がりと同じ 280px 角にして跳ねさせない */
.qr-wait {
	width: 280px;
	height: 280px;
	max-width: 100%;
	border-radius: var(--pico-border-radius);
	background: var(--ui-base-200);
	animation: qr-wait-pulse 1.5s ease-in-out infinite;
}
@keyframes qr-wait-pulse {
	50% {
		opacity: 0.5;
	}
}
@media (prefers-reduced-motion: reduce) {
	.qr-wait {
		animation: none;
	}
}
</style>
