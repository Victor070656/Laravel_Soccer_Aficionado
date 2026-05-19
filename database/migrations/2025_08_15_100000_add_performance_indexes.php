<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Posts — SoftDeletes filter on every query
        Schema::table('posts', function (Blueprint $table) {
            $table->index('deleted_at', 'posts_deleted_at_index');
            $table->index(['is_approved', 'created_at'], 'posts_approved_created_index');
            $table->index(['community_id', 'is_approved', 'created_at'], 'posts_community_approved_created_index');
            $table->index(['user_id', 'is_approved', 'created_at'], 'posts_user_approved_created_index');
        });

        // Comments — SoftDeletes + approval filter on every post view
        Schema::table('comments', function (Blueprint $table) {
            $table->index('deleted_at', 'comments_deleted_at_index');
            $table->index('is_approved', 'comments_is_approved_index');
            $table->index(['post_id', 'is_approved', 'created_at'], 'comments_post_approved_created_index');
        });

        // Notifications — paginated listing sorted by created_at
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'notifications_user_created_index');
        });

        // Polls — active polls query on dashboard
        Schema::table('polls', function (Blueprint $table) {
            $table->index(['is_active', 'closes_at', 'created_at'], 'polls_active_closes_created_index');
        });

        // Reports — admin moderation queue
        Schema::table('reports', function (Blueprint $table) {
            $table->index('reviewed_by', 'reports_reviewed_by_index');
            $table->index(['status', 'created_at'], 'reports_status_created_index');
        });

        // Point transactions — user history sorted by date
        Schema::table('point_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'point_transactions_user_created_index');
        });

        // Shares — per-post share listing
        Schema::table('shares', function (Blueprint $table) {
            $table->index(['post_id', 'created_at'], 'shares_post_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_deleted_at_index');
            $table->dropIndex('posts_approved_created_index');
            $table->dropIndex('posts_community_approved_created_index');
            $table->dropIndex('posts_user_approved_created_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_deleted_at_index');
            $table->dropIndex('comments_is_approved_index');
            $table->dropIndex('comments_post_approved_created_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_created_index');
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->dropIndex('polls_active_closes_created_index');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_reviewed_by_index');
            $table->dropIndex('reports_status_created_index');
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->dropIndex('point_transactions_user_created_index');
        });

        Schema::table('shares', function (Blueprint $table) {
            $table->dropIndex('shares_post_created_index');
        });
    }
};
