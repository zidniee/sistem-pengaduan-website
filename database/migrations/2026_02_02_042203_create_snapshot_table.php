<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_facts', function (Blueprint $table) {
            $table->date('date');
            $table->integer('platform_id')->constrained('platforms');
            $table->integer('dikirim');
            $table->integer('diterima')->default(0);
            $table->timestamps();

            $table->primary(['date', 'platform_id']);
        });


        DB::statement("CREATE OR REPLACE VIEW monthly_snapshots AS
            SELECT 
                platform_id,
                YEAR(date) as year,
                MONTH(date) as month,
                DATE_FORMAT(date, '%M') as month_name,
                SUM(dikirim) as dikirim_total,
                SUM(diterima) as diterima_total
            FROM daily_facts
            GROUP BY platform_id, year, month, month_name
        ");

        DB::statement("CREATE OR REPLACE VIEW yearly_snapshots AS
            SELECT 
                platform_id,
                YEAR(date) as year,
                SUM(dikirim) as dikirim_total,
                SUM(diterima) as diterima_total
            FROM daily_facts
            GROUP BY platform_id, year
        ");

        DB::connection()->getPdo()->exec("CREATE TRIGGER increment_daily_facts
            AFTER INSERT ON complaints
            FOR EACH ROW
            BEGIN
                INSERT INTO daily_facts (date, platform_id, dikirim, diterima)
                VALUES (NEW.submitted_at, NEW.platform_id, 1, 0)
                ON DUPLICATE KEY UPDATE 
                    dikirim = dikirim + 1;
            END");

        try {
            DB::connection()->getPdo()->exec("CREATE TRIGGER increment_daily_facts_update
            AFTER UPDATE ON complaints
            FOR EACH ROW
            BEGIN
                IF DATE(OLD.submitted_at) != DATE(NEW.submitted_at) OR OLD.platform_id != NEW.platform_id THEN
                    UPDATE daily_facts 
                    SET dikirim = dikirim - 1, updated_at = NOW()
                    WHERE date = DATE(OLD.submitted_at) AND platform_id = OLD.platform_id;
        
                    INSERT INTO daily_facts (date, platform_id, dikirim, diterima, created_at, updated_at)
                    VALUES (DATE(NEW.submitted_at), NEW.platform_id, 1, 0, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE 
                        dikirim = dikirim + 1,
                        updated_at = NOW();
                END IF;
            END");
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        

        DB::connection()->getPdo()->exec("CREATE TRIGGER decrement_daily_facts_delete
            AFTER DELETE ON complaints
            FOR EACH ROW
            BEGIN
                UPDATE daily_facts 
                SET dikirim = dikirim - 1, updated_at = NOW()
                WHERE date = DATE(OLD.submitted_at) AND platform_id = OLD.platform_id;

                DELETE FROM daily_facts 
                WHERE date = DATE(OLD.submitted_at) 
                  AND platform_id = OLD.platform_id 
                  AND dikirim = 0 
                  AND diterima = 0;
            END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_facts');
    }
};
